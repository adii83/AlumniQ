const CONFIG = {
    APP_NAME: "AlumniQ",
    APP_VERSION: "2.0.0",

    ROWS_PER_PAGE: 30,

    API: {
        CSV_FILE:    'data.csv',
        ALUMNI:      'api/alumni.php',
        STATS:       'api/stats.php',
        VALIDATE:    'api/validate.php',
        LOGIN:       'api/login.php',
        USE_BACKEND: true,
    },

    STATUS: {
        VALIDATED:     'Tervalidasi',
        NOT_VALIDATED: 'Perlu Divalidasi',
        PROCESSING:    'Data Abu-Abu',
    },

    COLUMNS: {
        NAME:              ['Nama Lulusan', 'Nama'],
        NIM:               'NIM',
        FACULTY:           'Fakultas',
        PROGRAM:           'Program Studi',
        GRADUATION_DATE:   ['Tanggal Lulus', 'Tahun Lulus'],
        ENTRY_YEAR:        'Tahun Masuk',
        PHONE:             'Nomor HP',
        EMAIL:             'Email',
        WORK_ADDRESS:      'Alamat Bekerja',
        CURRENT_WORKPLACE: 'Tempat Bekerja (Present)',
        CURRENT_POSITION:  'Posisi Jabatan (Present)',
        CURRENT_STATUS:    'Status Pekerjaan (Present)',
        LAST_WORKPLACE:    'Tempat Bekerja (Terakhir)',
        LAST_POSITION:     'Posisi Jabatan (Terakhir)',
        LAST_STATUS:       'Status Pekerjaan (Terakhir)',
        LAST_OFFICE_SOCIAL:'Sosmed Kantor (Terakhir)',
        LINKEDIN:          'Linkedin',
        INSTAGRAM:         'Instagram',
        TIKTOK:            'TikTok',
        FACEBOOK:          'Facebook',
    },

    TRACKING_COLUMNS: [
        'Linkedin', 'Instagram', 'TikTok', 'Facebook',
        'Tempat Bekerja (Present)', 'Posisi Jabatan (Present)', 'Status Pekerjaan (Present)',
        'Sosmed Kantor (Present)', 'Tempat Bekerja (Terakhir)', 'Posisi Jabatan (Terakhir)',
        'Status Pekerjaan (Terakhir)', 'Sosmed Kantor (Terakhir)',
        'Alamat Bekerja', 'Email', 'Nomor HP',
    ],
};
