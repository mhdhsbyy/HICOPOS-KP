{{-- Date & time picker themed like the transaction-history date filter, plus time (HH:MM AM/PM).
       Posts to hidden input named $name in 'Y-m-d\TH:i' format. --}}

<div>
    <label class="block text-sm font-bold text-[#2b1f17] mb-1.5">{{ $label }} <span class="text-[#b8a898] font-normal">(opsional)</span></label>

    <input type="hidden" name="{{ $name }}" id="{{ $id }}" value="{{ $value ?? '' }}">

    <div class="relative" x-data="hoDateTimePicker('{{ $id }}')" @click.outside="open = false">
        <button type="button" x-ref="button" @click="toggle()" @keydown.enter.prevent="enterKey()"
            class="w-full flex items-center justify-between gap-2 px-4 py-2.5 rounded-xl border border-[#f0e8e0] bg-white text-sm text-[#2b1f17] hover:border-[#764706] focus:outline-none focus:ring-2 focus:ring-[#764706]/30 transition cursor-pointer">
            <span class="truncate" :class="value ? '' : 'text-[#b8a898] font-medium'" x-text="display"></span>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#b0a090] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" /></svg>
        </button>

        <div x-show="open" x-cloak x-transition x-ref="popover"
            class="absolute z-30 w-[26rem] rounded-2xl bg-white shadow-xl border border-[#f0e8e0] p-4"
            :class="[flip ? 'bottom-full mb-2' : 'mt-2', alignRight ? 'right-0' : 'left-0']">
            <div class="flex gap-4">
                <div class="flex-1 min-w-0">
                    {{-- Month nav --}}
                    <div class="flex items-center justify-between mb-3">
                        <button type="button" @click="prevMonth()"
                            class="w-7 h-7 rounded-lg text-[#8b684b] hover:bg-[#f0e8e0] hover:text-[#5f4a3b] flex items-center justify-center transition cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" /></svg>
                        </button>
                        <span class="text-sm font-black text-[#2b1f17]" x-text="viewLabel"></span>
                        <button type="button" @click="nextMonth()"
                            class="w-7 h-7 rounded-lg text-[#8b684b] hover:bg-[#f0e8e0] hover:text-[#5f4a3b] flex items-center justify-center transition cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
                        </button>
                    </div>

                    {{-- Weekdays --}}
                    <div class="grid grid-cols-7 gap-1 mb-1">
                        <template x-for="w in weekdays" :key="w">
                            <span class="text-[10px] font-bold text-[#8b684b] uppercase text-center" x-text="w"></span>
                        </template>
                    </div>

                    {{-- Days --}}
                    <div class="grid grid-cols-7 gap-1">
                        <template x-for="(cell, i) in dayCells" :key="i">
                            <button type="button"
                                class="flex flex-col items-center justify-center rounded-lg text-xs font-bold leading-tight py-1 transition cursor-pointer"
                                :class="cell === null
                                    ? 'invisible'
                                    : (isSelected(cell)
                                        ? 'bg-[#764706] text-white'
                                        : (isToday(cell) ? 'text-[#764706] hover:bg-[#FFF0DA]' : 'text-[#2b1f17] hover:bg-[#FFF0DA]'))"
                                @click="cell !== null && select(cell)">
                                <span x-text="cell"></span>
                            </button>
                        </template>
                    </div>
                </div>

                {{-- Time --}}
                <div class="w-24 shrink-0 border-l border-[#f0e8e0] pl-4">
                    <p class="text-[10px] font-bold text-[#8b684b] uppercase tracking-wider mb-2">Waktu</p>

                    <label class="text-[10px] font-bold text-[#b0a090] uppercase tracking-wider">Jam</label>
                    <select @change="setHour($event)"
                        class="w-full px-2 py-1.5 rounded-lg border border-[#e2d3c2] bg-white text-sm font-bold text-[#2b1f17] focus:outline-none focus:ring-2 focus:ring-[#764706]/30 cursor-pointer mb-3">
                        <template x-for="h in hours" :key="h">
                            <option :value="h" :selected="h === hour" x-text="String(h).padStart(2, '0')"></option>
                        </template>
                    </select>

                    <label class="text-[10px] font-bold text-[#b0a090] uppercase tracking-wider">Menit</label>
                    <select @change="setMinute($event)"
                        class="w-full px-2 py-1.5 rounded-lg border border-[#e2d3c2] bg-white text-sm font-bold text-[#2b1f17] focus:outline-none focus:ring-2 focus:ring-[#764706]/30 cursor-pointer mb-3">
                        <template x-for="m in minutes" :key="m">
                            <option :value="m" :selected="m === minute" x-text="String(m).padStart(2, '0')"></option>
                        </template>
                    </select>

                    <label class="text-[10px] font-bold text-[#b0a090] uppercase tracking-wider">Periode</label>
                    <div class="flex rounded-lg overflow-hidden border border-[#e2d3c2]">
                        <button type="button" @click="setAmpm('AM')"
                            :class="ampm === 'AM' ? 'bg-[#764706] text-white' : 'bg-white text-[#8b684b] hover:bg-[#FFF0DA]'"
                            class="flex-1 px-2 py-1.5 text-xs font-black transition cursor-pointer">AM</button>
                        <button type="button" @click="setAmpm('PM')"
                            :class="ampm === 'PM' ? 'bg-[#764706] text-white' : 'bg-white text-[#8b684b] hover:bg-[#FFF0DA]'"
                            class="flex-1 px-2 py-1.5 text-xs font-black transition cursor-pointer">PM</button>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="mt-3 pt-3 border-t border-[#f0e8e0] flex items-center justify-between">
                <span class="text-[10px] font-bold text-[#b0a090]" x-text="value ? 'Dipilih: ' + display : 'Kosong (selalu aktif)'"></span>
                <div class="flex items-center gap-3">
                    <button type="button" @click="clear()"
                        class="text-xs font-bold text-red-500 hover:underline transition cursor-pointer">
                        Hapus
                    </button>
                    <button type="button" @click="selectNow()"
                        class="text-xs font-bold text-[#764706] hover:underline transition cursor-pointer">
                        Sekarang
                    </button>
                </div>
            </div>
        </div>
    </div>

    <x-input-error :messages="$errors->get($errorKey)" />
</div>

@once
<script>
function hoDateTimePicker(inputId) {
    const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    const weekdays = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
    const now = new Date();
    const pad = n => String(n).padStart(2, '0');
    const fmt = dt => `${dt.getFullYear()}-${pad(dt.getMonth() + 1)}-${pad(dt.getDate())}`;
    const parseInput = v => {
        if (!v || !/^\d{4}-\d{2}-\d{2}(T|\s)\d{2}:\d{2}/.test(v)) return null;
        const [d, t] = v.split(/[T\s]/);
        const [y, m, day] = d.split('-').map(Number);
        const [hh, mm] = t.split(':').map(Number);
        return new Date(y, m - 1, day, hh, mm);
    };

    return {
        open: false,
        flip: false,
        alignRight: false,
        value: document.getElementById(inputId).value,
        viewYear: now.getFullYear(),
        viewMonth: now.getMonth(),
        candidate: null,
        hour: (now.getHours() % 12) || 12,
        minute: now.getMinutes(),
        ampm: now.getHours() >= 12 ? 'PM' : 'AM',
        months: months,
        weekdays: weekdays,
        hours: Array.from({ length: 12 }, (_, i) => i + 1),
        minutes: Array.from({ length: 60 }, (_, i) => i),
        today: fmt(now),

        get selectedDay() {
            return this.candidate;
        },

        get display() {
            if (!this.value) return 'Kosong (selalu aktif)';
            const dt = parseInput(this.value);
            if (!dt) return 'Semua tanggal';
            const long = dt.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
            const h = (dt.getHours() % 12) || 12;
            const ampm = dt.getHours() >= 12 ? 'PM' : 'AM';
            return `${long}, ${pad(h)}:${pad(dt.getMinutes())} ${ampm}`;
        },

        get viewLabel() {
            return this.months[this.viewMonth] + ' ' + this.viewYear;
        },

        get dayCells() {
            const first = new Date(this.viewYear, this.viewMonth, 1);
            const offset = (first.getDay() + 6) % 7;
            const daysInMonth = new Date(this.viewYear, this.viewMonth + 1, 0).getDate();
            const cells = [];
            for (let i = 0; i < offset; i++) cells.push(null);
            for (let d = 1; d <= daysInMonth; d++) cells.push(d);
            while (cells.length % 7 !== 0) cells.push(null);
            return cells;
        },

        isToday(day) {
            return this.today === fmt(new Date(this.viewYear, this.viewMonth, day));
        },

        isSelected(day) {
            const c = this.candidate;
            return !!c && c.y === this.viewYear && c.m === this.viewMonth && c.d === day;
        },

        prevMonth() {
            this.viewMonth--;
            if (this.viewMonth < 0) { this.viewMonth = 11; this.viewYear--; }
        },

        nextMonth() {
            this.viewMonth++;
            if (this.viewMonth > 11) { this.viewMonth = 0; this.viewYear++; }
        },

        toggle() {
            if (!this.open) {
                const v = parseInput(this.value);
                if (v) {
                    this.candidate = { y: v.getFullYear(), m: v.getMonth(), d: v.getDate() };
                    this.viewYear = this.candidate.y;
                    this.viewMonth = this.candidate.m;
                    this.hour = (v.getHours() % 12) || 12;
                    this.minute = v.getMinutes();
                    this.ampm = v.getHours() >= 12 ? 'PM' : 'AM';
                } else {
                    this.candidate = { y: now.getFullYear(), m: now.getMonth(), d: now.getDate() };
                    this.viewYear = now.getFullYear();
                    this.viewMonth = now.getMonth();
                }
            }
            this.open = !this.open;
            this.$nextTick(() => this.computeFlip());
        },

        enterKey() {
            if (!this.open) {
                this.toggle();
                return;
            }
            this.selectNow();
        },

        computeFlip() {
            const btn = this.$refs.button.getBoundingClientRect();
            const pop = this.$refs.popover;
            this.flip = btn.bottom + pop.offsetHeight > window.innerHeight - 8;
            this.alignRight = btn.left + pop.offsetWidth > window.innerWidth - 8;
        },

        setValue() {
            let c = this.candidate;
            if (!c) {
                const v = parseInput(this.value);
                c = v ? { y: v.getFullYear(), m: v.getMonth(), d: v.getDate() } : { y: this.viewYear, m: this.viewMonth, d: 1 };
            }
            let hh = this.hour % 12;
            if (this.ampm === 'PM') hh += 12;
            this.value = `${c.y}-${pad(c.m + 1)}-${pad(c.d)}T${pad(hh)}:${pad(this.minute)}`;
            this.sync();
        },

        select(day) {
            this.candidate = { y: this.viewYear, m: this.viewMonth, d: day };
            this.setValue();
            this.open = false;
        },

        setHour(e) {
            this.hour = Number(e.target.value);
            this.setValue();
        },

        setMinute(e) {
            this.minute = Number(e.target.value);
            this.setValue();
        },

        setAmpm(v) {
            this.ampm = v;
            this.setValue();
        },

        selectNow() {
            this.candidate = { y: now.getFullYear(), m: now.getMonth(), d: now.getDate() };
            this.hour = (now.getHours() % 12) || 12;
            this.minute = now.getMinutes();
            this.ampm = now.getHours() >= 12 ? 'PM' : 'AM';
            this.setValue();
            this.open = false;
        },

        clear() {
            this.value = '';
            this.candidate = null;
            this.sync();
        },

        sync() {
            document.getElementById(inputId).value = this.value;
            document.getElementById(inputId).dispatchEvent(new Event('change'));
        },
    };
}
</script>
@endonce