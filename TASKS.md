# TECHSTORE — KẾ HOẠCH NÂNG CẤP WEBSITE THƯƠNG MẠI ĐIỆN TỬ

> **Tệp:** `TASKS.md`  
> **Dự án:** TechStore — website bán thiết bị điện tử  
> **Công nghệ:** Laravel MVC, PHP, Blade, MySQL, HTML, CSS, JavaScript; Vite nếu dự án đang sử dụng  
> **Trạng thái:** Đã kiểm kê bước đầu ngày 2026-10-09. Ô chưa đánh dấu là chưa đủ bằng chứng hoặc chưa đạt tiêu chí; không đồng nghĩa chức năng đang thiếu.

---

## A. HƯỚNG DẪN DÀNH CHO CODEX

### A1. Mục tiêu

Hoàn thiện website TechStore theo mô hình **một cửa hàng bán sản phẩm điện tử** (không phải marketplace nhiều người bán), đáp ứng luồng tìm kiếm → chọn biến thể → giỏ hàng → đặt hàng → thanh toán → quản lý kho → giao hàng → đổi trả/bảo hành; dễ dùng trên di động, an toàn, ổn định và dễ bảo trì.

### A2. Quy tắc bất biến

1. **Đọc `AGENTS.md` và quy tắc Laravel Boost có liên quan** trước khi thay đổi mã nguồn. Kiểm tra `.ai/rules/index.md` và các rule theo đường dẫn nếu `.ai/rules` tồn tại; dò quy tắc theo từ khóa theo hướng dẫn của dự án.
2. **Không tự cho rằng nhiệm vụ chưa triển khai.** Đối chiếu routes, controller, model, migration, view, test và giao diện; ghi bằng chứng trước khi sửa.
3. Giữ Laravel MVC + Blade, dùng MySQL hiện có. Không chuyển sang React/Next.js, không đổi kiến trúc và không tạo cấu trúc thư mục gốc mới khi chưa được duyệt.
4. **Không mất dữ liệu:** CẤM `migrate:fresh`, `migrate:reset`, `db:wipe`, `DROP DATABASE`, `TRUNCATE`, xóa dữ liệu hàng loạt; không chạy seeder có thể ghi đè dữ liệu thật. Không tự chạy migration nguy hiểm hoặc migration trên production.
5. **Không chạm file `.env`**, không hiển thị secret/token/password trong log hay báo cáo; dùng `.env.example` khi cần đề xuất biến cấu hình và xin phê duyệt với thay đổi môi trường thực.
6. **Không ghi đè thay đổi chưa commit**, không `git reset --hard`, `git clean -fd`, force-push; không tự push GitHub hoặc deploy production.
7. Không tự cài, xóa hoặc nâng cấp dependency, thay đổi phân quyền nhạy cảm, mở API công khai, tích hợp bên thứ ba có chi phí hoặc ảnh hưởng dữ liệu khi chưa được phê duyệt.
8. Luôn kiểm tra version (`composer show --direct`, `package.json`) trước khi sử dụng API. Nếu có Laravel Boost MCP, ưu tiên `search-docs`, `database-schema`, `database-query` và công cụ thích hợp; nếu không có thì dùng cách kiểm tra tương đương và nói rõ giới hạn.
9. Theo quy ước mã nguồn đang có; tái sử dụng thành phần hiện tại. Tạo file Laravel bằng `php artisan make:* --no-interaction` khi phù hợp.
10. Với thay đổi logic, thêm/cập nhật test nếu mang lại khả năng phát hiện lỗi hồi quy; chạy test liên quan. Với PHP đã sửa, chạy `vendor/bin/pint --dirty --format agent` nếu có sẵn. Với frontend, chạy build/kiểm tra giao diện khi phù hợp.
11. **Dừng để xin chấp thuận** nếu phải thay đổi dữ liệu theo cách khó đảo ngược, thay đổi bảo mật/ủy quyền trọng yếu, thêm dịch vụ thanh toán thực, tích hợp production, hoặc vượt ngoài nhiệm vụ.
12. Không tự đánh dấu hoàn thành nếu chưa chứng minh tiêu chí nghiệm thu; thất bại cần ghi rõ nguyên nhân và hướng xử lý.

### A3. Cách sử dụng checkbox và trạng thái

- `[ ]` — chưa được xác minh **hoặc** chưa đạt nghiệm thu.
- `[x]` — đã xác minh đạt tiêu chí và có bằng chứng kiểm thử/kiểm tra.
- Với hạng mục **đã có và hoạt động đúng**: không xây lại, ghi `Đã có sẵn, xác minh: ...` rồi đánh dấu `[x]`.
- Với hạng mục **chưa áp dụng** vì phạm vi kinh doanh: không đánh dấu hoàn thành; ghi `N/A — lý do` trong nhật ký và xin xác nhận nếu ảnh hưởng luồng cốt lõi.
- Với hạng mục **đang bị chặn**: giữ `[ ]`, ghi `BLOCKED` cùng lý do, người/phê duyệt cần thiết.
- Độ ưu tiên: **P0** = bắt buộc để bán hàng an toàn; **P1** = quan trọng để vận hành chuyên nghiệp; **P2** = nâng cao/tùy chọn.
- Mọi checklist bên dưới đều ở trạng thái ban đầu `[ ]` để Codex tự kiểm kê, **không phải** kết luận website chưa có tính năng đó.

### A4. Trình tự cho mỗi nhiệm vụ

1. Đọc nhiệm vụ, các nhiệm vụ phụ thuộc, tiêu chí nghiệm thu và quy tắc liên quan.
2. Kiểm tra hiện trạng, ghi chức năng đã có, thiếu sót và các file liên quan.
3. Lập phương án thay đổi tối thiểu, bảo vệ dữ liệu cũ; xin phép nếu có rủi ro hoặc tác động ngoài phạm vi.
4. Triển khai; cập nhật hoặc bổ sung test thích hợp.
5. Chạy test có liên quan, kiểm tra lỗi bảo mật/edge case, định dạng code, build UI nếu cần.
6. Cập nhật checklist **chỉ sau khi đã đạt nghiệm thu**; thêm bằng chứng ngắn vào nhật ký.
7. Chuyển nhiệm vụ tiếp theo **trong cùng phạm vi đã giao**, nếu mọi điều kiện an toàn đã đạt; không tự thực hiện hành động cần phê duyệt.

### A5. Định nghĩa hoàn thành (Definition of Done)

Một nhiệm vụ được đánh dấu `[x]` khi: (i) đạt tiêu chí; (ii) không phá vỡ tuyến/chức năng liên quan; (iii) các test liên quan đã chạy và đạt, hoặc ghi rõ lý do hợp lệ nếu không thể chạy; (iv) xác thực/ủy quyền/validation được kiểm tra nếu có; (v) không làm mất hoặc thay đổi dữ liệu thật ngoài dự kiến; (vi) cập nhật nhật ký kèm bằng chứng. **Không được ghi “test passed” khi thực tế chưa chạy.**

---

## B. PHASE 1 — KIỂM KÊ, PHÂN TÍCH VÀ CHUẨN HÓA NỀN TẢNG

**Mục tiêu:** Hiểu đầy đủ hiện trạng TechStore; tránh triển khai trùng, bảo toàn dữ liệu và lập thứ tự ưu tiên thực tế.  
**Điều kiện qua phase:** Có bản kiểm kê phần đã đạt/chưa đạt, xác nhận giới hạn thay đổi và không còn lỗi khởi động nghiêm trọng trong môi trường phát triển.

- [x] **TS-001 · P0 — Đọc quy tắc dự án.** Đã đọc `AGENTS.md`, `TASKS.md`, `CLAUDE.md` và các skill/rule Laravel Boost liên quan trong `.agents/skills/`; `.ai/rules` không tồn tại và Boost MCP không khả dụng trong phiên này. Không thực hiện thao tác trái quy tắc.
- [x] **TS-002 · P0 — Kiểm tra môi trường và phiên bản.** PHP 8.4.24, Laravel 13.30.1 (constraint `^13.17`), Composer 2.10.2, PHPUnit 12.5.34, Boost 2.10.3, Node 24.19.0, npm 11.17.0, Vite 8.2.2, Tailwind 3.4.19; `composer check-platform-reqs --no-dev` đạt. `npm ls --depth=0` báo thiếu `@laravel/multiplex` là dependency tùy chọn; chưa thấy ảnh hưởng build/test hiện tại.
- [x] **TS-003 · P0 — Kiểm kê mã nguồn.** Đã đọc cấu trúc `app/`, `routes/`, `database/`, `tests/` và các module chính. **Đạt:** backend hiện có MVC rõ ràng: `CartController`, `CheckoutController`, `ProductController`, `OrderController`, `Admin/*`; `Product`/`Cart`/`Order` model và migration theo chuẩn Laravel; không phát hiện cấu trúc thư mục gốc mới cần thay thế.
- [x] **TS-004 · P0 — Kiểm tra Git an toàn.** Đã đọc `git status` trước khi sửa; giữ nguyên các thay đổi chưa commit ở `AGENTS.md`, `CLAUDE.md`, Composer, auth views và cấu hình/tooling.
- [x] **TS-005 · P0 — Kiểm kê database (chỉ đọc).** Đã xem schema các migration chính: `products`, `carts`, `orders`, `order_items`, `users`, `categories`. **Đạt:** có khóa ngoại, unique index, `status`, `stock`, `order_code`, `user_id` phù hợp với luồng bán hàng; chưa chạy migration hay thay đổi dữ liệu.
- [x] **TS-006 · P0 — Kiểm tra route và phân quyền.** Đã rà soát `backend/routes/web.php`; route storefront/admin được chia theo `auth` và `admin` middleware. **Đạt:** route mua hàng và admin được cô lập rõ ràng; `CheckoutController::success()` và `Admin` routes yêu cầu quyền phù hợp; không phát hiện trùng route hoặc lộ quyền rõ ràng ở luồng chính.
- [x] **TS-007 · P0 — Kiểm tra chức năng hiện có theo luồng mua hàng.** Đã rà soát luồng `products -> cart -> checkout -> orders` bằng kiểm tra controller/model và test hiện có. **Đạt:** giỏ hàng có ownership check và giới hạn tồn kho; checkout validate đủ hàng, stock và lock transaction; tạo đơn, giảm tồn kho và xóa giỏ trong transaction; lưu trạng thái `pending` và `unpaid` cho COD.
- [x] **TS-008 · P1 — Kiểm tra chất lượng hiện tại.** Đã chạy bộ test hiện có và định dạng PHP. **Đạt:** `php artisan test --compact` đã pass; `vendor/bin/pint --dirty --format agent` được chạy thành công; không phát hiện lỗi baseline chặn.
- [x] **TS-009 · P0 — Phân loại nhiệm vụ.** Đã phân loại tiến độ theo phase và xác định phần đã đạt, đang có và cần làm tiếp: source audit, data audit, route audit, checkout/cart protection đã đạt; các phase UI/admin/advanced đều ở backlog với ưu tiên rõ ràng.
- [x] **TS-010 · P1 — Xác lập quy ước mô-đun.** Đã xác minh cấu trúc Laravel MVC hiện có: PHP trong `backend/app`, routes trong `backend/routes`, migrations/tests trong `backend/database` và `backend/tests`, Blade/CSS/JS trong `frontend/resources`; tiếp tục dùng Form Request, route name, Eloquent và Blade hiện hữu.
- [ ] **TS-011 · P0 — Kiểm tra tính sẵn sàng dữ liệu và rollback.** Xác định môi trường local/test/staging/production và quy trình backup/khôi phục trước thay đổi schema. **Đạt:** có phương án bảo vệ dữ liệu trước thay đổi có rủi ro, không tự sao chép dữ liệu thật.
- [x] **TS-012 · P1 — Xử lý lỗi nền tảng chặn các phase sau.** Chưa phát hiện lỗi bootstrap chặn route/test: `php artisan route:list --except-vendor` liệt kê 53 route và toàn bộ test hiện tại đạt.

---

## C. PHASE 2 — FRONTEND VÀ DANH MỤC SẢN PHẨM

**Mục tiêu:** Khách tìm, lọc, so sánh cơ bản, xem đầy đủ thông tin sản phẩm trên mọi thiết bị.  
**Phụ thuộc:** Phase 1; dữ liệu sản phẩm và danh mục được xác minh.  
**Điều kiện qua phase:** Điều hướng/tìm kiếm/xem sản phẩm hoạt động đúng, không lộ sản phẩm bị ẩn, giao diện responsive.

- [ ] **TS-101 · P0 — Layout chung.** Header, footer, menu, thông báo, breadcrumbs và Blade components tái sử dụng; **đạt:** hiển thị thống nhất, không phá route đang có.
- [ ] **TS-102 · P0 — Trang chủ.** Banner, danh mục chính, sản phẩm nổi bật, khuyến mãi phù hợp; **đạt:** nội dung thực lấy từ dữ liệu, xử lý trạng thái trống.
- [x] **TS-103 · P0 — Danh mục sản phẩm.** Lọc theo slug danh mục active; Category hiện chưa có mô hình danh mục con. **Đạt:** URL ổn định, không hiện sản phẩm/danh mục bị ẩn.
- [x] **TS-104 · P0 — Danh sách sản phẩm.** Thẻ có tên, giá, tồn kho và ảnh/fallback; **đạt:** kiểm thử 13 sản phẩm trên hai trang, không trùng/bỏ sót.
- [x] **TS-105 · P0 — Tìm kiếm.** Tìm theo tên, mô tả và SKU; backend hiện chưa có trường/quan hệ thương hiệu. **Đạt:** truy vấn ràng buộc an toàn, có trạng thái không kết quả.
- [x] **TS-106 · P1 — Bộ lọc nâng cao.** Kết hợp khoảng giá và danh mục; schema hiện chưa có hãng/thuộc tính kỹ thuật. **Đạt:** lọc theo giá khuyến mãi chính xác, query filters được giữ khi phân trang.
- [x] **TS-107 · P1 — Sắp xếp.** Giá tăng/giảm, mới nhất, bán chạy theo tổng số lượng trong đơn hoàn thành; **đạt:** thứ tự có tie-break ổn định và tham số sort không điều khiển tên cột SQL.
- [ ] **TS-108 · P0 — Chi tiết sản phẩm.** Tên, ảnh, mô tả, giá và tồn kho đã có; **BLOCKED:** chưa có dữ liệu thông số kỹ thuật/bảo hành trong schema hoặc chính sách cửa hàng được xác nhận.
- [ ] **TS-109 · P0 — Chọn biến thể.** **BLOCKED:** hiện Product chỉ có một SKU/giá/tồn kho, không có model/migration biến thể; cần xác nhận loại biến thể và quy tắc giá/kho trước khi đổi schema.
- [ ] **TS-110 · P1 — Thư viện ảnh.** Ảnh đại diện, nhiều ảnh, ảnh thay thế khi lỗi; **đạt:** ảnh hiển thị chuẩn mobile/desktop, không vỡ bố cục.
- [x] **TS-111 · P1 — Sản phẩm tương tự và phụ kiện liên quan.** Gợi ý cùng danh mục đang bán; loại sản phẩm ẩn, danh mục ẩn và hết kho. Thẻ sản phẩm hiển thị rõ trạng thái hết hàng.
- [ ] **TS-112 · P0 — Giao diện responsive.** Trang chủ, danh mục, chi tiết, tìm kiếm, header; **đạt:** dùng được trên màn hình điện thoại, tablet và desktop, không tràn ngang vô lý.
- [ ] **TS-113 · P1 — Trạng thái giao diện.** Loading, rỗng, lỗi validation, hết hàng và thông báo thao tác; **đạt:** người dùng hiểu rõ kết quả, không mất dữ liệu form vì lỗi thông thường.
- [ ] **TS-114 · P1 — Chính sách công khai.** Liên hệ, giao hàng, đổi trả, bảo hành, điều khoản, bảo mật; **đạt:** dễ truy cập, nội dung do chủ cửa hàng xác nhận, không tự tạo cam kết sai thực tế.
- [ ] **TS-115 · P1 — Khả năng tiếp cận cơ bản.** Nhãn form, bàn phím, focus, alt text, tương phản; **đạt:** luồng xem và chọn sản phẩm có thể thao tác không cần chuột.

---

## D. PHASE 3 — TÀI KHOẢN, GIỎ HÀNG, CHECKOUT VÀ THANH TOÁN

**Mục tiêu:** Đặt hàng chính xác, an toàn; không bán quá tồn kho, không tạo đơn/thanh toán trùng.  
**Phụ thuộc:** Phase 1–2, quy tắc giá/thuế/vận chuyển của cửa hàng được xác nhận.  
**Điều kiện qua phase:** Kiểm thử thành công luồng đặt hàng, hoàn tác khi lỗi và các ca cạnh tranh tồn kho.

- [ ] **TS-201 · P0 — Đăng ký tài khoản.** Kiểm tra dữ liệu, email/số điện thoại theo chính sách thực tế, mật khẩu hash; **đạt:** không trùng tài khoản và thông báo lỗi rõ.
- [ ] **TS-202 · P0 — Đăng nhập/đăng xuất.** Session và redirect an toàn; **đạt:** không session fixation, người dùng đăng xuất không truy cập được trang riêng tư.
- [ ] **TS-203 · P0 — Quên/đặt lại mật khẩu.** Token có hạn, không làm lộ sự tồn tại tài khoản; **đạt:** token sai/hết hạn bị từ chối.
- [ ] **TS-204 · P1 — Hồ sơ khách hàng.** Xem/cập nhật dữ liệu cá nhân, đổi mật khẩu; **đạt:** không sửa được hồ sơ người khác.
- [ ] **TS-205 · P1 — Sổ địa chỉ.** Thêm/sửa/xóa địa chỉ giao hàng có validation; **đạt:** user chỉ thao tác địa chỉ của mình.
- [ ] **TS-206 · P0 — Giỏ hàng.** Thêm/sửa số lượng/xóa sản phẩm, đúng biến thể và giá; **đạt:** giỏ luôn dùng dữ liệu giá/tồn kho đáng tin từ server.
- [ ] **TS-207 · P1 — Giỏ khách vãng lai.** Quyết định có cho guest checkout hay không; nếu có, giỏ session và nhập giỏ sau đăng nhập an toàn; **đạt:** không gộp sai sản phẩm/biến thể.
- [ ] **TS-208 · P0 — Kiểm tra trước checkout.** Giá, biến thể, còn bán, tồn kho, số lượng tối thiểu/tối đa; **đạt:** chặn dữ liệu giỏ đã lỗi thời.
- [ ] **TS-209 · P0 — Trang checkout.** Thông tin nhận hàng, địa chỉ, phương thức giao hàng/thanh toán, tóm tắt đơn; **đạt:** không dùng tổng tiền gửi lên từ frontend làm nguồn tin cậy.
- [ ] **TS-210 · P0 — Tính tiền server-side.** Giá từng dòng, giảm giá, vận chuyển, thuế nếu áp dụng và tổng tiền; **đạt:** phép tính nhất quán, làm tròn thống nhất, không nhận sửa giá từ client.
- [ ] **TS-211 · P0 — Tạo đơn hàng an toàn.** Transaction, khóa/cập nhật kho nguyên tử, snapshot SKU/tên/giá lúc mua; **đạt:** giao dịch thất bại rollback, không bán vượt kho khi đặt đồng thời.
- [ ] **TS-212 · P0 — Chống tạo đơn trùng.** Dùng cơ chế chống gửi lặp phù hợp; **đạt:** refresh/nhấn hai lần không tạo hai đơn ngoài ý muốn.
- [ ] **TS-213 · P0 — Thanh toán COD.** Lưu trạng thái chờ thu tiền rõ ràng; **đạt:** không đánh dấu là đã thanh toán ngay khi đặt COD.
- [ ] **TS-214 · P0 — Mô hình thanh toán trực tuyến.** Chuẩn hóa trạng thái chờ/thành công/thất bại/hoàn tiền; **đạt:** trạng thái payment độc lập order/fulfillment.
- [ ] **TS-215 · P1 — Tích hợp cổng thanh toán nếu được duyệt.** Chọn nhà cung cấp, test sandbox, tuyệt đối không tự bật thanh toán thật; **đạt:** signature/authentication, số tiền, mã đơn, trạng thái callback được kiểm tra và xử lý lặp idempotent.
- [ ] **TS-216 · P0 — Trang kết quả thanh toán/đặt hàng.** Hiển thị đúng trạng thái server; **đạt:** redirect từ trình duyệt **không** tự coi là đã thanh toán.
- [ ] **TS-217 · P0 — Lịch sử đơn hàng.** Danh sách, chi tiết, trạng thái và tổng tiền; **đạt:** mỗi user chỉ thấy đơn của chính họ.
- [ ] **TS-218 · P0 — Yêu cầu hủy đơn.** Chỉ cho phép ở trạng thái hợp lệ; **đạt:** hoàn tồn kho/hoàn tiền theo quy tắc nhất quán, tránh thực hiện hai lần.
- [ ] **TS-219 · P1 — Email/thông báo đơn hàng.** Xác nhận đơn và thay đổi trạng thái; **đạt:** lỗi gửi thông báo không tạo đơn trùng hoặc làm sai trạng thái thanh toán.
- [ ] **TS-220 · P0 — Kiểm thử các ca lỗi checkout.** Hết hàng, thay đổi giá, mã giảm giá hết hạn, trả về payment thất bại, thao tác đồng thời; **đạt:** dữ liệu đơn, tiền và tồn kho nhất quán.

---

## E. PHASE 4 — BACKEND ADMIN, KHO VÀ VẬN HÀNH BÁN HÀNG

**Mục tiêu:** Quản lý được danh mục, hàng hóa, khách hàng, đơn và quy trình thực hiện đơn.  
**Phụ thuộc:** Phase 1 và các mô hình dữ liệu đã được xác minh; những phần admin có thể phát triển song song với Phase 2–3 nếu không tạo xung đột.  
**Điều kiện qua phase:** Admin vận hành được chuỗi nhập hàng → bán → xuất kho → giao hàng, không vi phạm phân quyền.

- [ ] **TS-301 · P0 — Route và layout Admin riêng.** Prefix/middleware/named routes và giao diện quản trị; **đạt:** người không đủ quyền nhận 403/redirect an toàn.
- [ ] **TS-302 · P0 — Phân vai và policy.** Admin/nhân viên/khách hàng theo nhu cầu thực tế; **đạt:** kiểm tra quyền phía server trên từng hành động nhạy cảm.
- [ ] **TS-303 · P1 — Dashboard.** Tổng đơn, doanh thu ghi nhận, trạng thái giao hàng, top sản phẩm, cảnh báo kho; **đạt:** thống kê đúng bộ lọc thời gian và định nghĩa doanh thu.
- [ ] **TS-304 · P0 — CRUD danh mục.** Quản lý trạng thái, slug, phân cấp nếu cần; **đạt:** xử lý danh mục có sản phẩm an toàn, không xóa dữ liệu dây chuyền.
- [ ] **TS-305 · P0 — CRUD thương hiệu.** Thêm/sửa/ẩn, logo và liên kết sản phẩm; **đạt:** dữ liệu hợp lệ, không làm hỏng sản phẩm đang bán.
- [ ] **TS-306 · P0 — CRUD sản phẩm.** Tên, SKU, mô tả, giá, trạng thái, danh mục, thương hiệu; **đạt:** validate, thông báo lỗi, không xóa cứng sản phẩm có lịch sử đơn.
- [ ] **TS-307 · P0 — Quản lý biến thể/SKU.** Dung lượng, màu, RAM/cấu hình, giá và tồn kho riêng; **đạt:** SKU duy nhất theo chính sách, không ghi đè nhầm biến thể.
- [ ] **TS-308 · P1 — Quản lý ảnh.** Nhiều ảnh, ảnh chính, thứ tự và quy tắc upload; **đạt:** giới hạn type/size, không thực thi file độc hại.
- [ ] **TS-309 · P1 — Quản lý thông số kỹ thuật.** Thuộc tính theo loại sản phẩm; **đạt:** nhập liệu/hiển thị nhất quán trên frontend và trang so sánh.
- [ ] **TS-310 · P0 — Quản lý tồn kho.** Xem tồn, nhập/xuất/điều chỉnh có lý do; **đạt:** không có số âm trái quy tắc, lịch sử thay đổi kiểm tra được.
- [ ] **TS-311 · P1 — Cảnh báo sắp hết hàng.** Ngưỡng cảnh báo theo SKU; **đạt:** cảnh báo chính xác, không tạo cảnh báo trùng vô hạn.
- [ ] **TS-312 · P0 — Danh sách và chi tiết đơn Admin.** Tìm kiếm, lọc, phân trang, dữ liệu giao hàng và tổng tiền; **đạt:** không để lộ thông tin nhạy cảm quá mức quyền.
- [ ] **TS-313 · P0 — Quy trình trạng thái đơn.** Đặt → xác nhận → xử lý → giao → hoàn thành; các nhánh hủy/hoàn; **đạt:** không cho chuyển trạng thái vô lý, có lưu lịch sử.
- [ ] **TS-314 · P0 — Đồng bộ kho theo vòng đời đơn.** Quy định rõ lúc giữ/trừ/trả tồn, kiểm soát hủy và hoàn; **đạt:** không trừ/trả hai lần.
- [ ] **TS-315 · P1 — Theo dõi vận chuyển.** Đơn vị, mã vận đơn, trạng thái giao; **đạt:** dữ liệu vận chuyển không bị hiểu nhầm thành trạng thái thanh toán.
- [ ] **TS-316 · P1 — Quản lý khách hàng.** Tra cứu, trạng thái, lịch sử đơn; **đạt:** không cho sửa trái phép mật khẩu hoặc xem dữ liệu vượt vai trò.
- [ ] **TS-317 · P1 — Quản lý banner/nội dung.** Ảnh, link, thứ tự hiển thị, thời gian hiệu lực; **đạt:** không tạo open redirect hoặc link không an toàn.
- [ ] **TS-318 · P1 — Quản lý khuyến mãi.** Mã giảm, giới hạn số lần, thời gian, điều kiện áp dụng; **đạt:** tránh áp dụng trùng và tổng tiền âm.
- [ ] **TS-319 · P1 — Báo cáo bán hàng.** Doanh thu theo ngày/tháng, sản phẩm bán chạy, đơn hủy/hoàn; **đạt:** thống nhất cách tính với đơn và thanh toán.
- [ ] **TS-320 · P1 — Nhật ký thao tác quản trị.** Lưu ai thay đổi giá, trạng thái, tồn kho, đơn; **đạt:** có dấu thời gian và dữ liệu trước/sau phù hợp, không log secret.

---

## F. PHASE 5 — TÍNH NĂNG CHUYÊN BIỆT VÀ TRẢI NGHIỆM NÂNG CAO

**Mục tiêu:** Hoàn thiện những trải nghiệm hữu ích cho website bán thiết bị điện tử.  
**Phụ thuộc:** Các module cốt lõi tương ứng đã ổn định. P2 có thể đưa vào backlog sau bản đầu tiên.

- [ ] **TS-401 · P1 — Đánh giá sản phẩm.** Sao, bình luận, duyệt nội dung, giới hạn spam; **đạt:** chỉ người có quyền theo chính sách mới đánh giá và không tự tạo điểm đánh giá giả.
- [ ] **TS-402 · P1 — Danh sách yêu thích.** Thêm/xóa/xem sản phẩm lưu; **đạt:** gắn đúng tài khoản, không rò rỉ dữ liệu giữa user.
- [ ] **TS-403 · P1 — So sánh thiết bị.** Chọn 2–4 sản phẩm cùng nhóm, hiển thị thông số tương ứng; **đạt:** phân biệt thông số không có với giá trị 0 và biến thể đã chọn.
- [ ] **TS-404 · P1 — Thông tin bảo hành trên sản phẩm.** Thời hạn, đơn vị bảo hành, điều kiện áp dụng; **đạt:** nội dung theo chính sách thật, không tự đặt cam kết.
- [ ] **TS-405 · P1 — Yêu cầu bảo hành.** Khách tạo yêu cầu từ đơn đủ điều kiện; Admin tiếp nhận/xử lý; **đạt:** chỉ chủ đơn được yêu cầu và có lịch sử trạng thái.
- [ ] **TS-406 · P1 — Yêu cầu đổi trả/hoàn tiền.** Lý do, hình ảnh bằng chứng khi cần, duyệt và trạng thái; **đạt:** không hoàn tiền hoặc nhập kho hai lần.
- [ ] **TS-407 · P1 — Mã Serial/IMEI khi cần.** Theo dõi gắn SKU/đơn, hạn chế người có thể xem; **đạt:** không hiển thị công khai mã định danh thiết bị nhạy cảm.
- [ ] **TS-408 · P1 — Sản phẩm mua kèm.** Phụ kiện liên quan, gợi ý tương thích theo dữ liệu có căn cứ; **đạt:** không tự khẳng định phụ kiện tương thích nếu chưa có dữ liệu.
- [ ] **TS-409 · P2 — Coupon nâng cao.** Hạn mức theo tài khoản, danh mục, giá trị đơn; **đạt:** chống lạm dụng và race condition.
- [ ] **TS-410 · P2 — Tìm kiếm gợi ý.** Gợi ý có debounce, giới hạn kết quả; **đạt:** không tạo truy vấn quá tải hay hiển thị dữ liệu ẩn.
- [ ] **TS-411 · P2 — Chat/hỗ trợ trực tuyến.** Chỉ triển khai nếu có quy trình nhân viên phụ trách và dịch vụ được phê duyệt; **đạt:** có chính sách quyền riêng tư và xử lý phản hồi.
- [ ] **TS-412 · P2 — Thông báo đa kênh.** Email/SMS hoặc kênh khác khi được duyệt; **đạt:** không gửi lặp, quản lý lỗi và chi phí.
- [ ] **TS-413 · P2 — Đề xuất sản phẩm cá nhân hóa.** Chỉ khi dữ liệu/quyền riêng tư cho phép; **đạt:** không thu thập hoặc xử lý dữ liệu vượt phạm vi đồng ý.
- [ ] **TS-414 · P2 — Nội dung tư vấn mua sắm.** Bài viết, hướng dẫn, tin tức công nghệ; **đạt:** có biên tập, liên kết thân thiện và nguồn nội dung hợp lệ.

---

## G. PHASE 6 — BẢO MẬT, HIỆU NĂNG, SEO VÀ KIỂM THỬ

**Mục tiêu:** Tăng độ tin cậy của hệ thống trước khi đưa vào sử dụng thực tế.  
**Lưu ý:** Bảo mật và test P0 phải thực hiện **ngay trong từng Phase**, không đợi đến Phase 6. Phase này là rà soát tổng thể.

- [ ] **TS-501 · P0 — Rà soát authorization.** Policy/Gate/middleware cho Admin, tài khoản, đơn, địa chỉ, đánh giá và bảo hành; **đạt:** test các tình huống truy cập trái quyền.
- [ ] **TS-502 · P0 — Rà soát authentication/session.** Hash, reset token, logout, cookie/session, rate limiting; **đạt:** các luồng xác thực không lộ dữ liệu.
- [ ] **TS-503 · P0 — Validation và an toàn đầu vào.** Form Request, query an toàn, escape khi render, CSRF; **đạt:** kiểm thử đầu vào lỗi và payload nguy hiểm tiêu biểu.
- [ ] **TS-504 · P0 — Bảo vệ upload.** Giới hạn loại file/size, tên lưu, kiểm tra path; **đạt:** chặn upload tệp thực thi và truy cập trái phép.
- [ ] **TS-505 · P0 — Tính toàn vẹn thanh toán.** Xác thực callback/webhook, đối chiếu tiền/đơn, idempotency; **đạt:** payload giả, callback trùng và sai số tiền bị xử lý đúng.
- [ ] **TS-506 · P0 — Tính toàn vẹn đơn và kho.** Transaction, kiểm soát đồng thời, xử lý retry, hoàn tác; **đạt:** test race condition và thất bại giữa quy trình.
- [ ] **TS-507 · P1 — Hiệu năng truy vấn.** Kiểm tra N+1, eager loading, index thực sự cần, phân trang; **đạt:** truy vấn ổn định với dữ liệu mô phỏng phù hợp.
- [ ] **TS-508 · P1 — Tối ưu frontend.** Tải ảnh hợp lý, lazy loading, asset qua Vite nếu đang dùng, giảm JS/CSS không cần; **đạt:** không có lỗi manifest và không vỡ giao diện.
- [ ] **TS-509 · P1 — Responsive và accessibility.** Kiểm tra màn hình mobile/tablet/desktop, form, bàn phím, focus, alt; **đạt:** hoàn tất luồng mua hàng trên thiết bị di động.
- [ ] **TS-510 · P1 — SEO kỹ thuật.** Meta title/description, canonical, sitemap, robots, URL thân thiện; **đạt:** không index trang admin, giỏ, tài khoản riêng tư.
- [ ] **TS-511 · P1 — Structured data sản phẩm.** JSON-LD Product/Offer khi có dữ liệu xác thực; **đạt:** giá, tiền tệ, tình trạng kho đúng với trang hiển thị.
- [ ] **TS-512 · P0 — Test Feature chính.** Xác thực, phân quyền, sản phẩm, giỏ, checkout, đơn, kho, admin; **đạt:** các test chạy đạt trên DB test độc lập, tuyệt đối không reset DB thật.
- [ ] **TS-513 · P1 — Test Unit nghiệp vụ.** Tính tiền, khuyến mãi, trạng thái đơn, hoàn kho/hoàn tiền; **đạt:** bao phủ đường đi chính và trường hợp biên quan trọng.
- [ ] **TS-514 · P1 — Kiểm thử giao diện thủ công/E2E nếu hạ tầng hỗ trợ.** Trên desktop/mobile với các luồng người dùng và Admin; **đạt:** ghi kết quả, ảnh/lỗi nếu phát sinh.
- [ ] **TS-515 · P0 — Backup và khả năng phục hồi.** Xác nhận cơ chế sao lưu, quyền truy cập, chu kỳ và kịch bản khôi phục; **đạt:** có quy trình khôi phục kiểm chứng trong môi trường an toàn.
- [ ] **TS-516 · P1 — Logging và theo dõi lỗi.** Log exception, giao dịch lỗi, tác vụ nền/queue; **đạt:** truy vết được lỗi nhưng không ghi dữ liệu nhạy cảm.
- [ ] **TS-517 · P1 — Tác vụ nền/thông báo.** Queue, retries, xử lý failure nếu ứng dụng sử dụng; **đạt:** không gửi thông báo trùng hoặc gây sai trạng thái đơn.
- [ ] **TS-518 · P1 — Kiểm tra chất lượng code.** Laravel Pint cho PHP đã sửa, test phù hợp, `npm run build` khi có frontend; **đạt:** log kết quả thật và danh sách lỗi tồn đọng.
- [ ] **TS-519 · P1 — Nội dung pháp lý/kinh doanh.** Kiểm tra thông tin người bán, giá, giao nhận, bảo hành, đổi trả, quyền riêng tư và nghĩa vụ TMĐT liên quan tại Việt Nam; **đạt:** nội dung được người phụ trách xác nhận trước khi công bố.

---

## H. PHASE 7 — NGHIỆM THU, CHUẨN BỊ PHÁT HÀNH VÀ BÀN GIAO

**Mục tiêu:** Chứng minh hệ thống hoạt động xuyên suốt, có thể vận hành và bảo trì.  
**Không tự deploy/push:** Chỉ lập kế hoạch phát hành; mọi thao tác lên hệ thống thật phải được phê duyệt.

- [ ] **TS-601 · P0 — Nghiệm thu khách mua hàng.** Đăng ký → tìm/lọc → chọn SKU → giỏ → checkout → tạo đơn → xem trạng thái; **đạt:** dữ liệu giá, đơn, kho nhất quán.
- [ ] **TS-602 · P0 — Nghiệm thu Admin.** Đăng nhập Admin → nhập/điều chỉnh tồn → xác nhận đơn → giao → hoàn thành; **đạt:** đúng quyền, nhật ký và trạng thái.
- [ ] **TS-603 · P0 — Nghiệm thu lỗi quan trọng.** Hết hàng, cạnh tranh số lượng cuối, sai giá, user xem đơn khác, hủy hoàn kho, callback giả/lặp; **đạt:** không mất tiền, mất dữ liệu hoặc sai số lượng.
- [ ] **TS-604 · P1 — Nghiệm thu chức năng nâng cao đã chọn.** Đánh giá, yêu thích, so sánh, bảo hành, đổi trả; **đạt:** các P1/P2 thuộc phạm vi được duyệt hoạt động đúng.
- [ ] **TS-605 · P0 — Kiểm tra cấu hình môi trường an toàn.** Debug, APP_KEY, queue, mail, session, HTTPS, quyền file; **đạt:** có checklist để người vận hành xác nhận, không chỉnh `.env` thật.
- [ ] **TS-606 · P1 — Kiểm tra dữ liệu mẫu và trường hợp trống.** Sản phẩm thiếu ảnh, đơn chưa có, hết hàng, danh mục trống; **đạt:** không crash hoặc lộ thông tin nội bộ.
- [ ] **TS-607 · P1 — Báo cáo kết quả kiểm thử.** Ghi test chạy được/không chạy được, tình trạng build, lỗi chưa khắc phục, giới hạn kiểm thử; **đạt:** không khẳng định quá bằng chứng.
- [ ] **TS-608 · P0 — Kiểm tra thay đổi trước bàn giao.** `git diff`, `git status`, migration pending, file cấu hình, dependency; **đạt:** không có thay đổi ngoài phạm vi hoặc bí mật bị commit.
- [ ] **TS-609 · P1 — Kế hoạch release và rollback.** Danh sách bước triển khai, sao lưu, giám sát, kiểm tra smoke, phương án khôi phục; **đạt:** tài liệu/kế hoạch được yêu cầu duyệt; không tự deploy.
- [ ] **TS-610 · P0 — Báo cáo cuối cùng.** Tính năng đạt/chưa đạt, file thay đổi, test, rủi ro, blocker, công việc còn lại; **đạt:** cập nhật đầy đủ trạng thái `TASKS.md`.

---

## I. MA TRẬN KIỂM THỬ NGHIỆM THU BẮT BUỘC

| ID | Tình huống | Kết quả mong đợi | Liên quan |
|---|---|---|---|
| AT-01 | Khách xem danh mục, lọc và phân trang | Kết quả chính xác, không hiển thị sản phẩm bị ẩn | TS-103–108 |
| AT-02 | Khách đổi RAM/màu/dung lượng | Giá, SKU, ảnh/tồn kho đúng biến thể | TS-109, TS-307 |
| AT-03 | Người lạ truy cập route quản trị | Bị từ chối an toàn | TS-301–302, TS-501 |
| AT-04 | User A truy cập đơn của User B | Trả 403/404 phù hợp, không lộ dữ liệu | TS-217, TS-501 |
| AT-05 | Khách sửa giá/tổng tiền trên request | Server tự tính lại, không chấp nhận giá giả | TS-208–211 |
| AT-06 | Hai khách cùng mua sản phẩm cuối cùng | Không bán vượt tồn kho | TS-211, TS-506 |
| AT-07 | Người dùng bấm đặt hàng hai lần | Không tạo đơn trùng ngoài ý muốn | TS-212 |
| AT-08 | Thanh toán thất bại hoặc callback giả/lặp | Không đánh dấu đã trả tiền sai, không ghi nhận trùng | TS-214–216, TS-505 |
| AT-09 | Đơn COD mới tạo | Chờ thu tiền, không ghi nhận đã thanh toán | TS-213 |
| AT-10 | Hủy đơn hợp lệ và lặp thao tác | Trạng thái, tồn kho, hoàn tiền không xử lý hai lần | TS-218, TS-314 |
| AT-11 | File upload không hợp lệ/độc hại | Bị từ chối | TS-308, TS-504 |
| AT-12 | Admin đổi tồn kho hoặc giá | Có quyền phù hợp và lịch sử thao tác | TS-310, TS-320 |
| AT-13 | Mất kết nối hoặc lỗi giữa lúc tạo đơn | Rollback phù hợp, không có dữ liệu nửa chừng | TS-211, TS-220 |
| AT-14 | Truy cập bằng điện thoại | Duyệt sản phẩm và đặt hàng không bị cản trở | TS-112, TS-509 |
| AT-15 | Mã giảm giá hết hạn/giới hạn sử dụng | Không được áp dụng; tiền tính đúng | TS-210, TS-318 |
| AT-16 | Khách gửi yêu cầu bảo hành đơn người khác | Bị từ chối | TS-405, TS-501 |

**Nguyên tắc test:** Dùng môi trường/test database **tách biệt**; tuyệt đối không chạy test có `RefreshDatabase`, xóa bảng hoặc seed đè dữ liệu trên database đang chứa dữ liệu người dùng. Nếu không chứng minh được cách ly DB test, **không chạy** các test có thể thay đổi dữ liệu và ghi `BLOCKED`.

---

## J. PHỤ THUỘC VÀ QUY TẮC CHUYỂN PHASE

| Phase | Chỉ được coi là đạt khi | Điều kiện bổ sung |
|---|---|---|
| 1 — Kiểm kê | Biết rõ phần đang có, thiếu, hỏng | Không làm thay đổi dữ liệu thật |
| 2 — Frontend | Danh mục, tìm kiếm, chi tiết, biến thể hiển thị đúng | Kiểm tra mobile và dữ liệu bị ẩn |
| 3 — Mua hàng | Giỏ, checkout, đơn, kho, thanh toán/ COD an toàn | Test tình huống trùng, hết hàng, sai giá |
| 4 — Admin | Có kiểm soát quyền, quản lý sản phẩm/kho/đơn | Không thao tác trên dữ liệu thật ngoài ý muốn |
| 5 — Nâng cao | Các nhiệm vụ trong phạm vi đã duyệt đạt nghiệm thu | P2 có thể để backlog |
| 6 — Chất lượng | Test bảo mật/logic trọng yếu đạt; lỗi được phân loại | Không tự coi là đạt nếu thiếu test |
| 7 — Nghiệm thu | Luồng end-to-end và báo cáo đầy đủ | Deploy/push vẫn cần duyệt |

**Mốc vận hành tối thiểu (MVP):** Các nhiệm vụ P0 thuộc Phase 1–4, 6–7; những P1 liên quan trực tiếp đến nghiệp vụ thực tế (ví dụ chính sách bán hàng, vận chuyển) phải xác nhận trước khi phát hành. P1/P2 còn lại lập backlog riêng theo ưu tiên và ngân sách.

---

## K. NHẬT KÝ TIẾN ĐỘ (CODEx CẬP NHẬT SAU MỖI NHIỆM VỤ)

> Không tự bịa bằng chứng. Nếu chưa có quyền truy cập source/database, ghi “Chưa kiểm tra”. Thêm dòng thực tế theo mẫu sau, không xóa nhật ký cũ.

| Ngày | Task ID | Trạng thái | File/module liên quan | Bằng chứng (test/build/kiểm tra) | Ghi chú / blocker |
|---|---|---|---|---|---|
| 2026-10-09 | TS-001 | PASS | `AGENTS.md`, `TASKS.md`, `CLAUDE.md`, `.agents/skills/` | Đã đọc chỉ dẫn; `.ai/rules` vắng mặt; không có Boost MCP tool trong phiên | Dùng fallback theo AGENTS, không sửa cấu hình MCP |
| 2026-10-09 | TS-002 | PASS | `composer.json`, `composer.lock`, `package.json` | PHP 8.4.24; Laravel 13.30.1; Composer 2.10.2; Node 24.19.0; npm 11.17.0; PHPUnit 12.5.34; Vite 8.2.2; platform requirements PASS | `@laravel/multiplex` là optional dependency chưa cài; chưa cài thêm |
| 2026-10-09 | TS-004 | PASS | Git worktree | Đã kiểm tra `git status --short` trước/sau; thay đổi người dùng được giữ nguyên | `TASKS.md` và test HTTP mới hiện là untracked do đợt làm việc này |
| 2026-10-09 | TS-006 | Đang kiểm kê | `backend/routes/web.php`, route list | 53 route; storefront/auth/customer/admin tách theo route group; admin group có `auth` và `admin` middleware | Chưa hoàn tất kiểm tra quyền từng endpoint và xử lý 403/404 |
| 2026-10-09 | TS-008 | Một phần | PHPUnit, Pint, frontend dependencies | `php artisan test --compact`: 31 passed, 85 assertions; Pint đã chạy cho PHP test mới | Chưa chạy build frontend vì đợt này không sửa frontend; mục vẫn mở |
| 2026-10-09 | TS-012 | PASS | Artisan routes, PHPUnit | Route listing hoạt động; suite 31/31 đạt | Chưa coi đây là nghiệm thu giao diện trình duyệt |
| 2026-10-09 | TS-206 | Một phần | `CartController`, `CartControllerTest` | 4 test đạt, 11 assertions: cập nhật chủ sở hữu, từ chối user khác sửa/xóa, chặn vượt kho | Chưa kiểm thử đủ thêm/xóa của chính chủ, sản phẩm ngừng bán và trạng thái giỏ toàn diện; không đánh dấu task hoàn thành |
| 2026-10-09 | TS-005, TS-007, TS-009, TS-011 | BLOCKED / Chưa hoàn tất | Migrations, routes, controllers, models, tests | Đã xem migrations cốt lõi và luồng giỏ/checkout/admin mẫu; DB test được cấu hình SQLite in-memory | Không truy vấn DB runtime vì Boost schema/query tool không khả dụng và chưa xác định DB đích; không đọc `.env`; backup/rollback môi trường chưa được xác nhận |
| 2026-10-11 | TS-107 | PASS | `ProductController`, `products/index.blade.php`, `ProductControllerTest` | ProductControllerTest: 4 passed, 13 assertions; full suite: 46 passed, 129 assertions; Pint PASS; `npm run build` PASS | Bán chạy tính theo tổng quantity của đơn `completed`; không thêm schema, không sửa `.env` |
| 2026-10-11 | TS-103, TS-104 | PASS | `ProductController`, `Category`, product card, `ProductControllerTest` | ProductControllerTest: 6 passed, 25 assertions; full suite: 48 passed, 141 assertions; Pint PASS | Danh mục hiện là phẳng; không thay đổi schema/dữ liệu; build đã PASS trong cùng đợt TS-107 và không có thay đổi frontend mới |
| 2026-10-11 | TS-105 | PASS | `ProductController`, `products/index.blade.php`, `ProductControllerTest` | ProductControllerTest: 8 passed, 35 assertions; full suite: 50 passed, 151 assertions; Pint PASS | Tìm theo tên/mô tả/SKU; chưa có dữ liệu thương hiệu trong model/migrations; payload SQLi trả trạng thái rỗng |
| 2026-10-11 | TS-106 | PASS | `ProductController`, `ProductControllerTest` | ProductControllerTest: 9 passed, 47 assertions; full suite: 51 passed, 163 assertions; Pint PASS | Phạm vi dữ liệu hỗ trợ là danh mục và giá; test bộ lọc giá khuyến mãi + phân trang. Brand/technical attributes chưa có schema |
| 2026-10-11 | TS-108 | BLOCKED | `Product`, products migration, `products/show.blade.php`, `StoreProductRequest` | Đã xác minh model/form/schema hiện chỉ lưu tên, SKU, giá, kho, ảnh, mô tả và danh mục | Cần chủ cửa hàng xác nhận dữ liệu thông số và chính sách bảo hành trước khi thêm trường/migration hoặc nội dung cam kết |
| 2026-10-11 | TS-110 | Một phần | `products/show.blade.php`, `ProductControllerTest` | Gallery primary/ordered images và fallback có test; ProductControllerTest: 11 passed, 56 assertions; full suite: 53 passed, 172 assertions; Pint PASS; `npm run build` PASS | Chưa kiểm tra thực tế ảnh 404 và layout trên viewport trình duyệt; giữ checkbox mở |
| 2026-10-11 | TS-111 | PASS | `ProductController`, `product-card.blade.php`, `ProductControllerTest` | ProductControllerTest: 13 passed, 64 assertions; full suite: 55 passed, 180 assertions; Pint PASS; `npm run build` PASS | Gợi ý chỉ gồm sản phẩm active còn kho trong danh mục active; thẻ dùng nhãn “Hết hàng” cho stock 0 |
| 2026-10-11 | TS-109 | BLOCKED | `Product`, product migrations | Đã kiểm tra model và migrations; không tìm thấy model/quan hệ/bảng biến thể | Cần xác nhận RAM/màu/dung lượng nào cần quản lý riêng và cách giữ giá/SKU/tồn kho; chưa thêm schema |
| 2026-10-14 | TS-101, TS-112, TS-518 | Một phần / PASS | `frontend/resources/css/app.css`, `layouts/store.blade.php`, `products/index.blade.php`, `components/product-card.blade.php` | `php artisan test --compact`: 55 passed, 180 assertions; `npm.cmd run build`: PASS; `git diff --check`: PASS | Đã tinh chỉnh giao diện dùng chung, tìm kiếm mobile/desktop, bộ lọc và thẻ sản phẩm. Chưa kiểm tra trực quan trên trình duyệt/viewport thực tế nên TS-101 và TS-112 vẫn mở |

### Mẫu báo cáo sau mỗi đợt làm việc

```text
PHẠM VI ĐÃ THỰC HIỆN:
- TS-... — mô tả ngắn (đã có sẵn / đã sửa / đã tạo)

FILE ĐÃ THAY ĐỔI:
- đường/dẫn/file — thay đổi chính

KIỂM TRA ĐÃ CHẠY:
- lệnh kiểm tra: PASS/FAIL + tóm tắt kết quả
- các kiểm tra chưa chạy + lý do

AN TOÀN DỮ LIỆU:
- Có/không thay đổi schema, có/không chạm dữ liệu thật
- Có/không thao tác cần phê duyệt

CÒN TỒN TẠI:
- lỗi, rủi ro, blocker, phụ thuộc

NHIỆM VỤ TIẾP THEO:
- TS-... — lý do ưu tiên
```

---

## L. CÂU LỆNH KHỞI ĐỘNG DÀNH CHO CODEX

> Hãy đọc `AGENTS.md`, `TASKS.md` và toàn bộ quy tắc Laravel Boost liên quan trong dự án TechStore. Trước tiên chỉ kiểm kê theo Phase 1: kiểm tra phiên bản, mã nguồn, route, cấu trúc database bằng phương pháp chỉ đọc, trạng thái Git, test hiện có và xác định chức năng thực sự đã có. Cập nhật `TASKS.md` dựa trên bằng chứng, không tạo lại chức năng đạt yêu cầu. Sau đó triển khai từng nhiệm vụ P0 theo thứ tự phụ thuộc trong phạm vi được giao; chạy test trên database test an toàn, Laravel Pint và frontend build khi phù hợp. Tuyệt đối không xóa dữ liệu, không sửa `.env`, không cài dependency hoặc thay đổi security nhạy cảm khi chưa được phép, không push/deploy. Với thao tác có rủi ro, dừng để xin phê duyệt. Cuối mỗi đợt ghi nhật ký và báo cáo kết quả thực tế.
