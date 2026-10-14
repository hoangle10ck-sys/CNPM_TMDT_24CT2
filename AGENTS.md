# TECHSTORE — QUY TẮC CODEX

## 1. Mục tiêu

Nâng cấp website thương mại điện tử TechStore thành hệ thống chuyên nghiệp, ổn định, bảo mật, hiệu suất tốt và dễ bảo trì.

Ưu tiên tái sử dụng code hiện tại, tránh thay đổi kiến trúc không cần thiết.

## 2. Công nghệ

- Laravel MVC, PHP, Blade
- MySQL
- HTML, CSS, JavaScript
- Vite nếu dự án đang sử dụng
- Giữ nguyên phiên bản các thư viện hiện tại, trừ khi được phê duyệt thay đổi

## 3. Quy tắc bắt buộc

1. Giữ nguyên kiến trúc Laravel MVC.
2. Phân chia rõ logic Frontend và Backend trong cùng dự án.
3. Không chuyển sang React, Next.js hoặc framework khác.
4. Không xóa database hoặc dữ liệu người dùng.
5. Không chạy `migrate:fresh`, `db:wipe`, `migrate:reset` hoặc các lệnh phá hủy dữ liệu.
6. Không ghi đè hoặc xóa các thay đổi chưa commit của người dùng.
7. Không tự ý push GitHub hoặc deploy production.
8. Không tự ý sửa file `.env` hoặc tiết lộ thông tin nhạy cảm.
9. Không cài hoặc nâng cấp dependency khi chưa được phê duyệt.
10. Không bỏ qua kiểm thử đối với những thay đổi về hành vi hoặc logic.
11. Không tự ý thay đổi cơ chế xác thực, phân quyền hoặc chính sách bảo mật.
12. Không sửa những chức năng không liên quan đến nhiệm vụ hiện tại.
13. Không được tự ý thực hiện các hành động phá hủy dữ liệu, kể cả khi có lỗi phát sinh.

## 4. Quy tắc Laravel Boost

Nếu Laravel Boost MCP khả dụng:

- Ưu tiên sử dụng công cụ Laravel Boost.
- Kiểm tra phiên bản package trước khi dùng API.
- Sử dụng `search-docs` cho các thay đổi phụ thuộc vào API Laravel.
- Kiểm tra database schema trước khi tạo migration hoặc model.
- Đọc các quy tắc liên quan trong `.ai/rules/`.
- Giữ nguyên conventions của dự án.
- Dùng Artisan để tạo các thành phần Laravel.
- Sử dụng PHPUnit cho các bài kiểm thử.
- Chạy Laravel Pint sau khi chỉnh sửa PHP.

Nếu công cụ MCP không khả dụng, sử dụng phương pháp kiểm tra thông thường và báo cáo giới hạn gặp phải.

## 5. Quyền tự động làm việc

Codex được phép:

- Phân tích cấu trúc dự án.
- Đọc và chỉnh sửa code trong workspace.
- Tạo controller, model, service, component và Blade view.
- Tạo migration an toàn nhưng không tự ý chạy migration có nguy cơ làm mất dữ liệu.
- Chạy kiểm thử và build.
- Sửa lỗi phát sinh trong phạm vi nhiệm vụ.
- Tiếp tục nhiệm vụ tiếp theo khi nhiệm vụ trước đã hoàn thành và vượt qua các bước kiểm tra.
- Không cần xin phép cho từng chỉnh sửa thông thường trong phạm vi được giao.

Codex phải dừng và yêu cầu phê duyệt trước các hành động:

- Xóa hoặc thay đổi dữ liệu có nguy cơ mất mát.
- Thay đổi bảo mật nghiêm trọng.
- Cài đặt hoặc nâng cấp dependency.
- Push GitHub hoặc deploy.
- Truy cập hệ thống ngoài phạm vi dự án.

## 6. Quy trình thực hiện

1. Đọc `AGENTS.md` và các quy tắc Laravel Boost liên quan.
2. Đọc `TASKS.md`.
3. Phân tích cấu trúc hiện tại.
4. Xác định nhiệm vụ chưa hoàn thành.
5. Lập kế hoạch ngắn gọn cho nhiệm vụ.
6. Triển khai theo kiến trúc Laravel hiện có.
7. Chạy kiểm thử phù hợp.
8. Chạy Pint nếu có thay đổi PHP.
9. Build frontend nếu cần.
10. Cập nhật trạng thái nhiệm vụ trong `TASKS.md`.
11. Tiếp tục nhiệm vụ tiếp theo trong phạm vi được giao nếu không có rủi ro hoặc bước cần phê duyệt.
12. Báo cáo các thay đổi, kết quả kiểm thử và vấn đề còn tồn tại.

## 7. Nguyên tắc hoàn thành

Chỉ đánh dấu nhiệm vụ hoàn thành khi:

- Chức năng đã được triển khai theo yêu cầu.
- Không phát hiện lỗi trong những bài kiểm thử liên quan.
- Không phá vỡ chức năng hiện có theo phạm vi đã kiểm tra.
- Không vi phạm quy tắc bảo mật và dữ liệu.
- Những giới hạn chưa xác minh được ghi rõ trong báo cáo.

Nếu không thể hoàn thành nhiệm vụ, phải ghi rõ nguyên nhân và không được đánh dấu đã hoàn thành.