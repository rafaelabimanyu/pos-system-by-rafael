/**
 * Tiysa POS - Static SPA Core Script
 * Menggunakan LocalStorage sebagai database sementara
 */

const SPA = {
    init() {
        this.seedData();
        this.checkAuth();
        this.updateUserInfo();
        this.initLogout();
    },

    seedData() {
        // Seed Users
        if (!localStorage.getItem('tiysa_users')) {
            const defaultUsers = [
                { id: 1, name: 'Admin Tiysa', email: 'admin@tiysapos.com', role: 'admin' },
                { id: 2, name: 'Senja', email: 'senja@tiysapos.com', role: 'kasir' },
                { id: 3, name: 'Muthia', email: 'muthia@tiysapos.com', role: 'kasir' },
                { id: 4, name: 'Melani', email: 'melani@tiysapos.com', role: 'kasir' },
                { id: 5, name: 'Dorkas', email: 'dorkas@tiysapos.com', role: 'kasir' },
                { id: 6, name: 'Araxsa', email: 'araxsa@tiysapos.com', role: 'kasir' }
            ];
            localStorage.setItem('tiysa_users', JSON.stringify(defaultUsers));
        }

        // Seed Products
        if (!localStorage.getItem('tiysa_products')) {
            const defaultProducts = [
                { id: 1, name: 'Beras Premium 5kg', price: 75000, buy_price: 65000, stok: 50, category: 'Kebutuhan Pokok' },
                { id: 2, name: 'Minyak Goreng 2L', price: 34000, buy_price: 30000, stok: 100, category: 'Kebutuhan Pokok' },
                { id: 3, name: 'Gula Pasir 1kg', price: 16000, buy_price: 14500, stok: 80, category: 'Kebutuhan Pokok' },
                { id: 4, name: 'Mie Instan Goreng', price: 3000, buy_price: 2500, stok: 200, category: 'Makanan & Minuman' },
                { id: 5, name: 'Air Mineral 600ml', price: 3500, buy_price: 2000, stok: 150, category: 'Makanan & Minuman' },
                { id: 6, name: 'Sabun Mandi Cair', price: 25000, buy_price: 21000, stok: 40, category: 'Perawatan Pribadi' },
                { id: 7, name: 'Deterjen Bubuk 800g', price: 22000, buy_price: 18000, stok: 60, category: 'Rumah Tangga' }
            ];
            localStorage.setItem('tiysa_products', JSON.stringify(defaultProducts));
        }

        // Seed Transactions
        if (!localStorage.getItem('tiysa_transactions')) {
            const defaultTransactions = [];
            // Buat beberapa transaksi dummy untuk hari ini
            const today = new Date();
            for(let i=0; i<5; i++) {
                const isDebit = Math.random() > 0.5;
                defaultTransactions.push({
                    id: 'TRX-00' + (i+1),
                    date: today.toISOString(),
                    total: 50000 + (i * 15000),
                    payment_method: isDebit ? 'debit' : 'cash',
                    cashier: 'Senja',
                    items: [
                        { name: 'Produk Dummy', qty: 2, subtotal: 50000 + (i * 15000) }
                    ]
                });
            }
            localStorage.setItem('tiysa_transactions', JSON.stringify(defaultTransactions));
        }
    },

    checkAuth() {
        const session = localStorage.getItem('tiysa_session');
        const currentPage = window.location.pathname.split('/').pop();
        
        // Skip auth check di halaman login
        if (currentPage === 'index.html' || currentPage === '') {
            if (session) {
                const user = JSON.parse(session);
                window.location.href = user.role === 'admin' ? 'dashboard.html' : 'pos.html';
            }
            return;
        }

        // Redirect jika belum login
        if (!session) {
            window.location.href = 'index.html';
            return;
        }

        // Role protection (Kasir tidak boleh buka dashboard)
        const user = JSON.parse(session);
        if (user.role !== 'admin' && (currentPage === 'dashboard.html' || currentPage === 'pengaturan.html' || currentPage === 'users.html')) {
            alert('Akses Ditolak: Halaman ini khusus Administrator.');
            window.location.href = 'pos.html';
        }
    },

    updateUserInfo() {
        const session = localStorage.getItem('tiysa_session');
        if (session) {
            const user = JSON.parse(session);
            // Update semua elemen yang menampilkan nama/email user
            document.querySelectorAll('.user-name-display').forEach(el => el.textContent = user.name);
            document.querySelectorAll('.user-email-display').forEach(el => el.textContent = user.email);
            document.querySelectorAll('.user-role-display').forEach(el => el.textContent = user.role.toUpperCase());
        }
    },

    initLogout() {
        const logoutBtns = document.querySelectorAll('.btn-logout');
        logoutBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                localStorage.removeItem('tiysa_session');
                window.location.href = 'index.html';
            });
        });
    },

    formatCurrency(amount) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(amount);
    },
    
    getProducts() {
        return JSON.parse(localStorage.getItem('tiysa_products')) || [];
    },
    
    saveProducts(products) {
        localStorage.setItem('tiysa_products', JSON.stringify(products));
    },
    
    getTransactions() {
        return JSON.parse(localStorage.getItem('tiysa_transactions')) || [];
    },
    
    saveTransaction(trx) {
        const trxs = this.getTransactions();
        trxs.push(trx);
        localStorage.setItem('tiysa_transactions', JSON.stringify(trxs));
    }
};

// Auto initialize jika tidak di index.html (index.html punya script sendiri di bawah)
if (!window.location.pathname.endsWith('index.html') && window.location.pathname !== '/') {
    document.addEventListener('DOMContentLoaded', () => {
        SPA.init();
    });
}
