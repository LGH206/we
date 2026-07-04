/**
 * admin_dashboard.js
 * Dashboard – gọi API /api/statistics, vẽ biểu đồ động từ dữ liệu DB thực tế
 * Tối ưu hóa: Đồng bộ các mốc tháng tự động và loại bỏ hoàn toàn dữ liệu mẫu (mock data)
 */

document.addEventListener('DOMContentLoaded', function() {
    const API_BASE = '/web/Backend/routes/api.php';
    let dashboardChart = null;

    // 1. Tải số bình luận đang chờ duyệt
    fetch(`${API_BASE}/api/comments?status=pending&limit=1`)
        .then(res => res.json())
        .then(pendingData => {
            const element = document.getElementById('pendingComments');
            if (element) {
                element.textContent = pendingData.total || 0;
            }
        })
        .catch(err => console.error('Lỗi tải bình luận chờ duyệt:', err));

    // 2. Lấy dữ liệu thống kê lưu lượng từ database
    fetch(`${API_BASE}/api/statistics`)
        .then(res => res.json())
        .then(data => {
            const ctx = document.getElementById('dashboardChart');
            if (!ctx) return;

            // --- GIẢI PHÁP TỐI ƯU ĐỒNG BỘ THỜI GIAN ---
            // Gom tất cả các mốc tháng xuất hiện trong DB của cả 3 bảng để tạo trục hoành (X-axis) duy nhất
            const rawPosts = data.posts_by_month || [];
            const rawUsers = data.users_by_month || [];
            const rawComments = data.comments_by_month || [];

            const allMonthsSet = new Set([
                ...rawPosts.map(item => item.month),
                ...rawUsers.map(item => item.month),
                ...rawComments.map(item => item.month)
            ]);

            // Sắp xếp các tháng theo thứ tự thời gian tăng dần
            const sortedMonths = Array.from(allMonthsSet).sort();

            // Hàm ánh xạ số liệu: Nếu tháng nào thiếu trong bảng dữ liệu gốc thì tự động điền 0
            function alignDataWithMonths(rawData, monthsList) {
                const map = {};
                rawData.forEach(item => {
                    map[item.month] = parseInt(item.total) || 0;
                });
                return monthsList.map(m => map[m] || 0);
            }

            // Đồng bộ 3 tập dữ liệu khớp 100% với trục hoành
            const posts = alignDataWithMonths(rawPosts, sortedMonths);
            const users = alignDataWithMonths(rawUsers, sortedMonths);
            const comments = alignDataWithMonths(rawComments, sortedMonths);

            // 3. Khởi tạo vẽ biểu đồ (Không dùng dữ liệu mẫu)
            dashboardChart = new Chart(ctx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: sortedMonths, // Trục hoành tự động co giãn theo các tháng có trong DB
                    datasets: [
                        { 
                            label: 'Bài viết', 
                            data: posts, 
                            borderColor: '#2e7d32', 
                            backgroundColor: 'rgba(46, 125, 50, 0.1)',
                            borderWidth: 2,
                            tension: 0.3,
                            fill: false 
                        },
                        { 
                            label: 'Người dùng', 
                            data: users, 
                            borderColor: '#1976d2', 
                            backgroundColor: 'rgba(25, 118, 210, 0.1)',
                            borderWidth: 2,
                            tension: 0.3,
                            fill: false 
                        },
                        { 
                            label: 'Bình luận', 
                            data: comments, 
                            borderColor: '#f57c00', 
                            backgroundColor: 'rgba(245, 124, 0, 0.1)',
                            borderWidth: 2,
                            tension: 0.3,
                            fill: false 
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: true,          // Bật hiển thị các đường kẻ vạch dọc
                                drawOnChartArea: true  // Vẽ các đường kẻ xuyên suốt vùng đồ thị
                            }
                        },
                        y: {
                            min: 0,           
                            suggestedMax: 5,  
                            grid: {
                                display: true,          // Bật hiển thị các đường kẻ vạch ngang
                                drawOnChartArea: true  // Vẽ các đường kẻ xuyên suốt vùng đồ thị
                            },
                            ticks: {
                                stepSize: 1,  
                                callback: function(value) {
                                    if (value % 1 === 0) {
                                        return value;
                                    }
                                }
                            }
                        }
                    }
                }
            });
        })
        .catch(err => {
            console.error('Lỗi tải dữ liệu thống kê từ database:', err);
        });

    // 4. Nút tạo báo cáo
    document.getElementById('reportBtn')?.addEventListener('click', function() {
        window.location.href = `${API_BASE}/api/dashboard/report`;
    });
});