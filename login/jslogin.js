const signUpButton = document.getElementById('signUp');
const signInButton = document.getElementById('signIn');
const container = document.getElementById('container');
// Các phần tử của form đăng ký
const NameSU = document.querySelector('.NameSU');
const EmailSU = document.querySelector('.EmailSU');
const PassSU = document.querySelector('.PassSU');
const NumberSU = document.querySelector('.NumberSU');
const AddressSU = document.querySelector('.AddressSU');
const signUpErrorMessage = document.getElementById('signUpErrorMessage'); // Giả sử bạn có một phần tử để hiển thị lỗi

signUpButton.addEventListener('click', () => {
    container.classList.add("right-panel-active");
});

signInButton.addEventListener('click', () => {
    container.classList.remove("right-panel-active");
}); 

// Hàm đăng ký với thông báo lỗi hiển thị
function signUp() {
    if (NameSU.value === "" || EmailSU.value === "" || PassSU.value === "" || NumberSU.value === "" || AddressSU.value === "") {
        // Hiển thị thông báo lỗi thay vì sử dụng alert
        signUpErrorMessage.textContent = "Vui lòng điền đầy đủ dữ liệu.";
    } else {
        const user = {
            username: NameSU.value,  // Sửa lỗi chính tả ở đây
            email: EmailSU.value,
            password: PassSU.value,
            number: NumberSU.value,
            address: AddressSU.value
        };
        let json = JSON.stringify(user);
        localStorage.setItem(EmailSU.value, json);  // Lưu dữ liệu bằng email làm khóa
        alert("Đăng ký thành công");
        window.open("../form-home/ASM1.html");
    }
}

// Hàm đăng nhập với khóa chính xác cho localStorage
const EmailSI = document.querySelector('.EmailSI');
const PassSI = document.querySelector('.PassSI');

function signIn() {
    if (EmailSI.value === "" || PassSI.value === "") {
        alert("Nhập đầy đủ dữ liệu");
    } else {
        const user = JSON.parse(localStorage.getItem(EmailSI.value));  // Sửa khóa thành EmailSI.value
        if (user && user.email === EmailSI.value && user.password === PassSI.value) {
            alert("Đăng nhập thành công");
            window.open("../form-home/ASM1.html");  // Có thể trả về ở đây để ngừng thực thi thêm
        } else {
            alert("Sai tài khoản hoặc mật khẩu");
        }
    }
}
