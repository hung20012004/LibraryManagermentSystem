<div class="container-fluid">
      <?php if (isset($_SESSION['message'])): ?>
        <div id="alert-message" class="alert alert-<?= $_SESSION['message_type']; ?> alert-dismissible fade show"
            role="alert">
            <?= $_SESSION['message']; ?>
        </div>
        <?php
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
        ?>
        <!-- <script>
            setTimeout(function () {
                var alert = document.getElementById('alert-message');
                if (alert) {
                    alert.classList.remove('show');
                    alert.classList.add('fade');
                    setTimeout(function () {
                        alert.style.display = 'none';
                    }, 150);
                }
            }, 7000); // 7000 milliseconds = 7 seconds
        </script> -->
    <?php endif; ?>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Tạo phiếu thanh lý tài sản</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="index.php?model=phieuthanhly&action=create" id="phieuNhapForm">
                <!-- Các trường thông tin chung của phiếu nhập -->
                <div class="form-group row">
                    <label for="nguoiNhap" class="col-sm-2 col-form-label">Người tạo phiếu:</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="nguoiNhap" name="nguoi_nhap" value="<?= htmlspecialchars($_SESSION['ten']); ?>" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="ngayNhap" class="col-sm-2 col-form-label">Ngày tạo phiếu:</label>
                    <div class="col-sm-10">
                        <input type="date" class="form-control" id="ngayNhap" name="ngay_tao" value="<?= date('Y-m-d'); ?>" readonly>
                    </div>
                </div>
                <!-- <div class="form-group row">
                    <label for="ngayXacNhan" class="col-sm-2 col-form-label">Ngày phê duyệt:</label>
                    <div class="col-sm-10">
                        <input type="date" class="form-control" id="ngayXacNhan" name="ngay_xac_nhan">
                    </div>
                </div> -->
                
                <h5 class="mt-4">Chi tiết phiếu thanh lý</h5>
                <table id="chiTietTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Tên tài sản</th>
                            <th>Số lượng</th>
                            <th>Tình trạng</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select class="form-control select-tai-san" name="tai_san_id[]" required>
                                    <option value="">Chọn tài sản</option>
                                    <?php foreach ($tai_san_list as $tai_san): ?>
                                        <option value="<?= $tai_san['tai_san_id']; ?>">
                                            <?= htmlspecialchars($tai_san['ten_tai_san']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>
                                <input type="number" class="form-control" name="so_luong[]" required min="1">
                            </td>
                            <td>
                                <select class="form-control select-tinh-trang" name="tinh_trang[]" required>
                                    <option value="Moi">Mới</option>
                                    <option value="Tot">Tốt</option>
                                    <option value="Kha">Khá</option>
                                    <option value="TrungBinh">Trung bình</option>
                                    <option value="Kem">Kém</option>
                                    <option value="Hong">Hỏng</option>
                                </select>
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm" onclick="removeTaiSan(this)">Xóa</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <button type="button" class="btn btn-primary" onclick="addTaiSan()">Thêm tài sản</button>

                <div class="form-group mt-3">
                    <label for="ghiChu">Ghi chú:</label>
                    <textarea class="form-control" id="ghiChu" name="ghi_chu" rows="3"></textarea>
                </div>

                <div class="form-group row">
                    <div class="col-sm-10">
                        <button type="submit" class="btn btn-primary">Lưu và gửi</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function addTaiSan() {
    var table = document.getElementById('chiTietTable').getElementsByTagName('tbody')[0];
    var newRow = table.insertRow(table.rows.length);
    var cell1 = newRow.insertCell(0);
    var cell2 = newRow.insertCell(1);
    var cell3 = newRow.insertCell(2);
    var cell4 = newRow.insertCell(3);

    cell1.innerHTML = document.querySelector('.select-tai-san').outerHTML;
    cell2.innerHTML = '<input type="number" class="form-control" name="so_luong[]" required min="1">';
    cell3.innerHTML = document.querySelector('.select-tinh-trang').outerHTML;
    cell4.innerHTML = '<button type="button" class="btn btn-danger btn-sm" onclick="removeTaiSan(this)">Xóa</button>';

    newRow.querySelector('.select-tai-san').addEventListener('change', function () {
        mergeDuplicateRows();
    });

    newRow.querySelector('.select-tinh-trang').addEventListener('change', function () {
        mergeDuplicateRows();
    });
}

function removeTaiSan(button) {
    var row = button.closest('tr');
    if (document.querySelectorAll('#chiTietTable tbody tr').length > 1) {
        row.parentNode.removeChild(row);
    } else {
        alert('Không thể xóa dòng cuối cùng.');
    }
}

function mergeDuplicateRows() {
    var table = document.getElementById('chiTietTable');
    var rows = table.getElementsByTagName('tr');
    var rowsToDelete = [];

    for (var i = 1; i < rows.length; i++) {
        for (var j = i + 1; j < rows.length; j++) {
            var taiSan1 = rows[i].querySelector('.select-tai-san').value;
            var tinhTrang1 = rows[i].querySelector('.select-tinh-trang').value;
            var taiSan2 = rows[j].querySelector('.select-tai-san').value;
            var tinhTrang2 = rows[j].querySelector('.select-tinh-trang').value;

            if (taiSan1 === taiSan2 && tinhTrang1 === tinhTrang2) {
                var soLuong1 = parseInt(rows[i].querySelector('input[name="so_luong[]"]').value);
                var soLuong2 = parseInt(rows[j].querySelector('input[name="so_luong[]"]').value);
                rows[i].querySelector('input[name="so_luong[]"]').value = soLuong1 + soLuong2;
                rowsToDelete.push(rows[j]);
            }
        }
    }

    for (var i = rowsToDelete.length - 1; i >= 0; i--) {
        rowsToDelete[i].parentNode.removeChild(rowsToDelete[i]);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.select-tai-san').forEach(function (select) {
        select.addEventListener('change', function () {
            mergeDuplicateRows();
        });
    });

    document.querySelectorAll('.select-tinh-trang').forEach(function (select) {
        select.addEventListener('change', function () {
            mergeDuplicateRows();
        });
    });
});
</script>