export default (entangled) => ({
    value: entangled,
    init() {
        this.formatPlate();
    },
    formatPlate() {
        if (!this.value) {
            this.value = '';
            return;
        }

        let val = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
        let formatted = '';

        if (val.length === 0) {
            formatted = '';
        } else if (/^[A-Z]\d{0,3}[A-Z]{0,2}$/.test(val)) {
            if (val.length <= 1) formatted = val;
            else if (val.length <= 4) formatted = val.slice(0, 1) + '-' + val.slice(1);
            else formatted = val.slice(0, 1) + '-' + val.slice(1, 4) + '-' + val.slice(4);
        } else if (/^[A-Z]{2}\d{0,3}[A-Z]{0,1}$/.test(val)) {
            if (val.length <= 2) formatted = val;
            else if (val.length <= 5) formatted = val.slice(0, 2) + '-' + val.slice(2);
            else formatted = val.slice(0, 2) + '-' + val.slice(2, 5) + '-' + val.slice(5);
        } else if (/^\d{0,2}[A-Z]{0,3}\d{0,1}$/.test(val)) {
            if (val.length <= 2) formatted = val;
            else if (val.length <= 5) formatted = val.slice(0, 2) + '-' + val.slice(2);
            else formatted = val.slice(0, 2) + '-' + val.slice(2, 5) + '-' + val.slice(5);
        } else if (/^\d{0,1}[A-Z]{0,3}\d{0,2}$/.test(val)) {
            if (val.length <= 1) formatted = val;
            else if (val.length <= 4) formatted = val.slice(0, 1) + '-' + val.slice(1);
            else formatted = val.slice(0, 1) + '-' + val.slice(1, 4) + '-' + val.slice(4);
        } else if (/^[A-Z]{3}\d{0,2}[A-Z]{0,1}$/.test(val)) {
            if (val.length <= 3) formatted = val;
            else if (val.length <= 5) formatted = val.slice(0, 3) + '-' + val.slice(3);
            else formatted = val.slice(0, 3) + '-' + val.slice(3, 5) + '-' + val.slice(5);
        } else if (/^[A-Z]{0,1}\d{0,2}[A-Z]{0,3}$/.test(val)) {
            if (val.length <= 1) formatted = val;
            else if (val.length <= 3) formatted = val.slice(0, 1) + '-' + val.slice(1);
            else formatted = val.slice(0, 1) + '-' + val.slice(1, 3) + '-' + val.slice(3);
        } else if (/^\d{0,1}[A-Z]{0,2}\d{0,3}$/.test(val)) {
            if (val.length <= 1) formatted = val;
            else if (val.length <= 3) formatted = val.slice(0, 1) + '-' + val.slice(1);
            else formatted = val.slice(0, 1) + '-' + val.slice(1, 3) + '-' + val.slice(3);
        } else {
            formatted = val;
        }

        this.value = formatted;
    },
    handleBackspace(event) {
        let input = event.target;
        let pos = input.selectionStart;
        if (pos === 0) return;
        if (input.value[pos - 1] === '-') {
            input.setSelectionRange(pos - 1, pos - 1);
        } else {
            input.value = input.value.slice(0, pos - 1) + input.value.slice(pos);
            input.setSelectionRange(pos - 1, pos - 1);
        }
        this.value = input.value;
    }
});
