# Project bài kiểm tra 2 của môn PHP

## Cấu trúc thư mục:
app/        
  |--controller/ Chứa Controller xử lý logic        
  |--models/ Chứa các Model lưu dữ liệu     
  |--views/ Chứa các View hiển thị      
bootstraps/ Chứa các Class cấu hình cơ bản của dự án     
config/ Chứa các cấu hình của dự án     
  |--config_example: File cấu hình mẫu      
css/ Style css của web    
  |--bootstrap-5/ Thư mục Boostrap 5  
js/ Javascript của web  
  |--bootstrap-5/ Thư mục Boostrap 5  
index.php: Web khởi chạy từ file này (vui lòng không chỉnh sửa)     
README.md: File hướng dẫn       

## Đối với các thành viên trong nhóm:

### Các bước để bắt đầu đóng góp
1. Clone project về thư mục htdocs của XAMPP hoặc là thư mục gốc của container (nếu dùng Docker)
2. Trước khi chạy, copy file config_example thành 1 file mới và đổi tên thành config.php rồi thay tên CSDL
3. Tiến hành code
4. Code xong hãy tạo branch riêng để commit trước rồi tạo merge request (tránh conflict với main)
5. **Vui lòng không chỉnh sửa branch của người khác trừ khi được cho phép**

### 1 số lưu ý

#### Đường dẫn để chèn ảnh hoặc lưu file ảnh vào csdl:
Tất cả đường dẫn ảnh, CSS, JS đều viết bắt đầu từ thư mục gốc của project. Ví dụ: `assets/images/test.png`


#### Đường dẫn khi chuyển hướng giữa các web với nhau
Giả sử bạn muốn truy cập vào `home` trong khi view hiện tại đang ở tận trong 1 folder (VD: `product/index`), thì để đổi hướng về trang home, dùng đường dẫn trong thẻ html như sau:

```php
// Chỉ trong trường hợp backend đã code Controller
<?= BASE_URL . "/home" >
```

#### Cách tạo Controller cho đúng
Khi tạo 1 controller mới, cú pháp sẽ phải là `Tên (viết hoa chữ cái đầu) + Controller` (VD: `ProductController.php`)  
Bên trong Controller, để tên class đúng bằng tên file, kèm theo việc extends class `Controller` cơ bản.   
Quy ước khi đặt tên hàm trong Controller:   
- `index()`: Hiển thị danh sách   
- `view($id)`: Xem chi tiết   
- `create()`: Tạo mới   
- `update($id)`: Cập nhật, sửa    
- `delete($id)`: Xóa    
Các hàm trên cùng với tên Controller sẽ được sử dụng trong đường dẫn chuyển hướng
VD: Khi muốn xem toàn bộ `Product`, đường dẫn sẽ như sau: `đường dẫn trong BASE_URL/product/index` (không cần index cũng được)

```php
class ProductController extends Controller{
    
public function index() {
  $title = "Tên tiêu đề trang";

  $data = [
    'WEBSITE_TITLE' => htmlspecialchars($title, FILTER_SANITIZE_URL);
      // Truyền data vào đây
  ];
  
  // Render view
  $this->view('Tên view trong thư mục views', $data);
}
```

#### Cách tạo model cho đúng
Đặt tên model với cú pháp: `Tên đối tượng (viết hoa chữ cái đầu) + Model`, extends class Model cơ bản và implement `RepositoryInterface`    
VD:

```php
class Product extends Model implements RepositoryInterface
{
    // Import đầy đủ các hàm thêm sửa xóa của RepositoryInterface
    // Có thể thêm 1 số hàm khác để dùng trong trường hợp đặc biệt nếu muốn
}
```

#### Để test view mà không muốn chờ backend làm Controller (Dành cho frontend)
Trong thư mục controller, đã có sẵn HomeController. Chỉ cần vào đó và viết thêm 1 hàm (nhỡ bôi đen lại sau khi test xong)   

```php
public function test() {
  $title = "Tiêu đề gì gì đó"

  // Dữ liệu truyền xuống view
  $data = [
      // Chống XSS khi đặt title
      'WEBSITE_TITLE' => htmlspecialchars($title, FILTER_SANITIZE_URL);
  ];

  // Render view
  $this->view('Tên view ở trong thư mục views', $data);
}
```

#### Phòng tránh xung đột giữa branch của mình và main (trong trường hợp main đã có những commit mới)
Trước khi yêu cầu hợp nhất branch của mình và main, hãy commit tất cả chỉnh sửa lên branch của mình trước rồi làm các bước sau:
1. Sang branch main: `git checkout main`
2. Pull các commit mới về main của mình: `git pull origin main`
3. Merge main với branch của mình: `git merge main`
4. Nếu xảy ra xung đột: 