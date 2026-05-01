const formatNumber = (num) => {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
};

const shuffleArray = (array) => {
    let currentIndex = array.length, randomIndex;
    while (currentIndex !== 0) {
        randomIndex = Math.floor(Math.random() * currentIndex);
        currentIndex--;
        [array[currentIndex], array[randomIndex]] = [array[randomIndex], array[currentIndex]];
    }
    return array;
};

const seededShuffle = (array, seed) => {
    let s = parseInt(seed) || 0;
    const lcg = () => {
        s = (Math.imul(s, 1664525) + 1013904223) | 0;
        return (s >>> 0) / 0xFFFFFFFF;
    };
    const arr = [...array];
    for (let i = arr.length - 1; i > 0; i--) {
        const j = Math.floor(lcg() * (i + 1));
        [arr[i], arr[j]] = [arr[j], arr[i]];
    }
    return arr;
};

const safeGet = (obj, keys, defaultValue = '-') => {
    if (typeof keys === 'string') {
        return (obj[keys] && String(obj[keys]).trim() !== '') ? String(obj[keys]).trim() : defaultValue;
    }
    if (Array.isArray(keys)) {
        for (const key of keys) {
            if (obj[key] && String(obj[key]).trim() !== '') {
                return String(obj[key]).trim();
            }
        }
    }
    return defaultValue;
};

const extractYear = (dateString) => {
    if (!dateString) return '-';
    const parts = String(dateString).split(' ');
    return parts.pop();
};

const formatTanggalLulus = (rawDate) => {
    if (!rawDate || rawDate === '-') return 'Belum Ditemukan';

    const BULAN = [
        '', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];

    const str = String(rawDate).trim();

    const isoMatch = str.match(/^(\d{4})-(\d{2})-(\d{2})/);
    if (isoMatch) {
        const d = parseInt(isoMatch[3], 10);
        const m = parseInt(isoMatch[2], 10);
        const y = isoMatch[1];
        if (m >= 1 && m <= 12) return `${d} ${BULAN[m]} ${y}`;
    }

    const dmyMatch = str.match(/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})/);
    if (dmyMatch) {
        const d = parseInt(dmyMatch[1], 10);
        const m = parseInt(dmyMatch[2], 10);
        const y = dmyMatch[3];
        if (m >= 1 && m <= 12) return `${d} ${BULAN[m]} ${y}`;
    }

    const serial = parseInt(str, 10);
    if (!isNaN(serial) && serial > 10000 && serial < 60000) {
        const d = new Date(Date.UTC(1899, 11, 30) + serial * 86400000);
        const day   = d.getUTCDate();
        const month = d.getUTCMonth() + 1;
        const year  = d.getUTCFullYear();
        if (month >= 1 && month <= 12) return `${day} ${BULAN[month]} ${year}`;
    }

    return str;
};

const EMPTY_DB_VALUES = [
    '-', '', 'tidak publik', 'tidak dicantumkan',
    'belum ada', 'n/a', 'null', 'none',
];

const normalizeDisplay = (val) => {
    if (!val) return 'Belum Ditemukan';
    if (EMPTY_DB_VALUES.includes(String(val).trim().toLowerCase())) return 'Belum Ditemukan';
    return String(val).trim();
};

const isValidLink = (value) => {
    if (!value) return false;
    const v = String(value).trim().toLowerCase();
    return v !== '' && !EMPTY_DB_VALUES.includes(v);
};

const debounce = (func, delay) => {
    let timeoutId;
    return function (...args) {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => func(...args), delay);
    };
};
