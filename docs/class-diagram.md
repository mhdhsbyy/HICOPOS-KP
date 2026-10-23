# Class Diagram — Hideout Coffee POS

Disusun dari kode sumber (`app/Models`, `app/Http/Controllers`, `app/Support`, `database/migrations`).
Render otomatis di GitHub/GitLab/Obsidian, atau di VS Code pakai ekstensi *Markdown Preview Mermaid Support*.

> **Catatan penting:** kolom di bawah adalah **skema database nyata hasil migrasi** (termasuk hasil
> `ALTER`Zones setelahnya), bukan `$fillable`. Yang menentukan apakah sebuah kolom bisa diubah Request ada di
> properti `$fillable` tiap model — keduanya sengaja dibedakan.

---

## 1. Domain Model (Eloquent + skema tabel)

Ini bagian paling penting. Struktur data inti POS beserta relasinya.

```mermaid
classDiagram
    direction LR

    class User {
        +bigint id
        +string username
        +string first_name
        +string last_name
        +string email
        +datetime email_verified_at
        +string phone
        +string photo
        +string password
        +string status
        +datetime created_at
        +datetime updated_at
        +getFullNameAttribute() string
        +getAvatarUrlAttribute() string
        +transactions() HasMany
        +hasRole() bool
    }

    class Category {
        +bigint id
        +string name
        +datetime created_at
        +datetime updated_at
        +menus() HasMany
    }

    class Menu {
        +bigint id
        +string code
        +string name
        +text description
        +text recipe
        +string photo
        +int price
        +bigint category_id FK
        +string status
        +datetime created_at
        +datetime updated_at
        +category() BelongsTo
        +transactionItems() HasMany
        +getThumbUrlAttribute() string
        +categoryPrefix() string
        +generateCode() string
        +reindexCodes() void
    }

    class Transaction {
        +bigint id
        +bigint user_id FK
        +decimal subtotal
        +decimal discount
        +decimal promo_discount
        +decimal total
        +decimal amount_paid
        +decimal change
        +string payment_method
        +string status
        +text notes
        +datetime created_at
        +datetime updated_at
        +getCodeAttribute() string
        +user() BelongsTo
        +items() HasMany
        +promos() BelongsToMany
    }

    class TransactionItem {
        +bigint id
        +bigint transaction_id FK
        +bigint menu_id FK
        +int quantity
        +decimal discount
        +decimal price
        +decimal total
        +datetime created_at
        +datetime updated_at
        +transaction() BelongsTo
        +menu() BelongsTo
    }

    class Promo {
        +bigint id
        +string name
        +text description
        +string type
        +decimal value
        +string scope
        +bigint target_id
        +int min_quantity
        +decimal min_amount
        +datetime start_date
        +datetime end_date
        +bool is_active
        +datetime created_at
        +datetime updated_at
        +menu() BelongsTo
        +category() BelongsTo
        +target() BelongsTo
        +transactions() BelongsToMany
    }

    class TransactionPromo {
        +bigint id
        +bigint transaction_id FK
        +bigint promo_id FK
        +decimal discount_amount
        +datetime created_at
        +datetime updated_at
    }

    Category "1" --> "0..*" Menu : punya
    Menu "1" --> "0..*" TransactionItem : dijual
    Transaction "1" --> "1..*" TransactionItem : berisi
    User "1" --> "0..*" Transaction : membuat
    Transaction "*" --> "*" Promo : terapkan

    note for User "Kolom `name` sudah dihapus. Migration 2026_04_09_020920 me-rename jadi `username`,<br/>plus menambah first_name, last_name, status. Gunakan accessor `full_name` dan `avatar_url`."
    note for Menu "code = UNIQUE + nullable, format {INITIAL-KATEGORI}-### (KO-001).<br/>Dihasilkan ulang oleh reindexCodes() saat menu dihapus atau pindah kategori."
    note for Transaction "code = ACCESSOR, bukan kolom. TRX-0003 + sufiks -XX per kategori unik.<br/>WAJIB eager-load items.menu.category, kalau tidak sufiksnya tidak muncul."
    note for TransactionPromo "TIDAK ADA unique constraint pada (transaction_id, promo_id).<br/>Ini penyebab bug diskon dobel — lihat AGENTS.md Known Bugs #2."
```

### Tabel pivot `transaction_promos`

Bukan model Eloquent — tabel murni yang diakses via `Transaction::promos()` dengan `withPivot('discount_amount')`.

```mermaid
erDiagram
    TRANSACTIONS ||--o{ TRANSACTION_ITEMS : "berisi"
    TRANSACTIONS ||--o{ TRANSACTION_PROMOS : "menerapkan"
    PROMOS ||--o{ TRANSACTION_PROMOS : "diterapkan_pada"
    MENUS ||--o{ TRANSACTION_ITEMS : "dijual"
    CATEGORIES ||--o{ MENUS : "mengelompokkan"
    USERS ||--o{ TRANSACTIONS : "membuat"
    USERS }o--o{ ROLES : "Spatie hasRole"

    TRANSACTIONS {
        bigint id PK
        bigint user_id FK
        decimal subtotal
        decimal discount "default 0"
        decimal promo_discount "default 0"
        decimal total
        decimal amount_paid
        decimal change "default 0"
        string payment_method "default cash"
        string status "default completed"
        text notes "nullable"
    }

    TRANSACTION_ITEMS {
        bigint id PK
        bigint transaction_id FK
        bigint menu_id FK
        int quantity
        decimal discount "default 0"
        decimal price
        decimal total
    }

    PROMOS {
        bigint id PK
        string name
        string type "percentage | flat"
        decimal value
        string scope "item | category | transaction"
        bigint target_id "nullable, polymorphic-ish"
        int min_quantity "nullable"
        decimal min_amount "nullable"
        datetime start_date "nullable"
        datetime end_date "nullable"
        bool is_active "default true"
    }
```

> ⚠ `PROMOS.target_id` **bukan foreign key**. Nilainya mengacu ke `menus.id` bila `scope = 'item'`,
> atau `categories.id` bila `scope = 'category'`. Tidak ada constraint DB — integritas dijamin hanya oleh
> logika `Promo::target()`. Menarik relasi ini lewat `$promo->menu` / `$promo->category`.

---

## 2. Lapisan Controller

Kimat memetakan controller → model yang dipakai, plus method yang tidak aman.

```mermaid
classDiagram
    direction TB

    class Controller {
        <<abstract>>
    }

    class OwnerDashboardController {
        +__invoke() View
    }
    class OwnerTransactionController {
        +page() View
        +index() View
        +indexFilter() Json
        +printIndex() Response
        +create() View
        +store() Response
        +show() View
        +kasirShow() View
        +receiptPdf() Response
        +cancel() Redirect
        +history() View
        +historyFilter() Json
        +chartFilter() Json
        +loadMoreMenus() Json
        -calculatePromoDiscount() float
    }
    class OwnerMenuController {
        +index() View
        +create() View
        +store() Redirect
        +show() View
        +edit() View
        +update() Redirect
        +destroy() Redirect
        +toggleStatus() Redirect
        +loadMore() Json
    }
    class OwnerCategoryController {
        +index() View
        +create() View
        +store() Redirect
        +edit() View
        +update() Redirect
        +destroy() Redirect
    }
    class OwnerCashierController {
        +index() View
        +create() View
        +store() Redirect
        +edit() View
        +update() Redirect
        +destroy() Redirect
        +toggleStatus() Redirect
    }
    class OwnerPromoController {
        +index() View
        +create() View
        +store() Redirect
        +edit() View
        +update() Redirect
        +destroy() Redirect
        +toggleStatus() Redirect
    }

    class CashierDashboardController {
        +__invoke() View
    }
    class CashierTransactionController {
        +create() View
        +store() Response
        +show() View
        +receiptPdf() Response
        +cancel() Redirect
        +history() View
        +historyFilter() Json
        +loadMoreMenus() Json
        -calculatePromoDiscount() float
    }

    class ProfileController {
        +edit() View
        +update() Redirect
        +updatePhoto() Redirect
        +destroy() Redirect
    }

    class ReceiptBuilder {
        <<static>>
        +build() array
        +toResponse() Response
    }

    Controller <|-- OwnerDashboardController
    Controller <|-- OwnerTransactionController
    Controller <|-- OwnerMenuController
    Controller <|-- OwnerCategoryController
    Controller <|-- OwnerCashierController
    Controller <|-- OwnerPromoController
    Controller <|-- CashierDashboardController
    Controller <|-- CashierTransactionController
    Controller <|-- ProfileController

    OwnerTransactionController ..> Transaction : orchestrates
    CashierTransactionController ..> Transaction : orchestrates
    OwnerTransactionController ..> ReceiptBuilder : prints
    CashierTransactionController ..> ReceiptBuilder : prints
    OwnerCashierController ..> User : manages
    OwnerMenuController ..> Menu : manages
    OwnerPromoController ..> Promo : manages
    OwnerCategoryController ..> Category : manages
    ProfileController ..> User : manages
    ReceiptBuilder ..> Transaction : reads

    note for OwnerTransactionController "DUPLIKASI disengaja:
    calculatePromoDiscount() di sini
    DAN di Cashier\TransactionController
    logikanya identik tapi terpisah.
    Ubah keduanya atau keduanya."
```

> ⚠ `Owner\TransactionController::page()` **tidak mengirim `$categories`** ke view, padahal
> `page.blade.php:61` membutuhkannya → HTTP 500. Saudaranya `create()` (`:363`) mengirimnya dengan benar.

---

## 3. Alur utama: `store()` (checkout POS)

Berlaku identik untuk `Owner` dan `Cashier` (duplikat disengaja agar laporan/the ownership berbeda).

```mermaid
sequenceDiagram
    autonumber
    actor Kasir
    participant View as Blade + Alpine posApp
    participant C as TransactionController
    participant M as Model / DB
    participant R as ReceiptBuilder

    Note over View: pos_cart & pos_state di localStorage

    Kasir->>View: isi keranjang, pilih metode bayar
    View->>View: hitung diskon 3 lapis di client<br/>(item → promo → manual)

    Kasir->>C: POST /transactions<br/>items & applied_promos sebagai STRING JSON
    C->>C: json_decode() + merge()
    C->>C: validate()

    C->>M: Menu::findOrFail() untuk tiap item
    Note right of C: harga di-SERVER baca ulang dari DB.<br/>Client tak bisa memalsukan harga.

    C->>M: Promo::active()->whereIn(id)
    loop tiap entri applied_promos
        C->>C: calculatePromoDiscount()
        Note right of C: ⚠ BUG: $promoDiscount += setiap iterasi<br/>padahal pivot dedup by id
    end

    C->>C: total = max(0, subtotal - item - promo - manual)
    Note right of C: ⚠ diskon manual TIDAK punya batas atas

    alt amount_paid < total
        C-->>Kasir: back() + "Jumlah bayar kurang dari total."
    else cukup
        C->>M: DB::beginTransaction()
        C->>M: Transaction::create()
        C->>M: items()->create() per baris
        C->>M: promos()->attach()
        C->>M: DB::commit()
        alt print_receipt = truthy
            C->>R: toResponse()
            R-->>Kasir: PDF struk 80mm (inline)
        else
            C-->>Kasir: redirect + flash "sukses"
        end
    end
```

---

## 4. Peta Ownership (kompleksitas siklikal)

Untuk pembaca yang masuk lewat halaman shared dan bingung "file mana yang asli?".

| Method | Render view | Scope data |
|---|---|---|
| `Owner::history()` / `historyFilter()` | `cashier.transactions.history` | `Auth::id()` — **milik owner sendiri** |
| `Owner::index()` / `indexFilter()` | `owner.transactions._data_table` | **Global** (semua transaksi) |
| `Owner::show()` | `owner.transactions.show` | Global, **tanpa** cek `user_id` |
| `Owner::kasirShow()` | `cashier.transactions.show` | Global, **tanpa** cek `user_id` |
| `Cashier::show()` / `receiptPdf()` / `cancel()` | `cashier.transactions.show` | `Auth::id()`, **403** bila bukan milik sendiri |

⚠ **`Owner\TransactionController::page()` bukan bagian dari pola ini.** Tab "Riwayat" di halaman itu
adalah **tabel inline terpisah** di `owner/transactions/page.blade.php`, bukan partial
`_history_table`. Edit `_history_table` **tidak** otomatis tercermin di sana — harus disinkronkan manual.

---

## Legenda

- **Panah `→`** = asosiasi / pemanggilan
- **Panah `..>`** = dependensi lemah (controller memakai model)
- **`<|--`** = pewarisan
- **Angka kardinalitas** = `1`, `*` (nol atau lebih), `0..*`
- **Kotak kuning** = catatan jebakan / tempat yang mudah salah
