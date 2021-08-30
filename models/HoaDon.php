<?php
require_once 'models/Model.php';
require_once 'models/DichVu.php';
require_once 'models/KhachHang.php';
require_once 'models/NhanVien.php';
require_once 'models/PhongHat.php';
class HoaDon extends Model
{
    private $cn;

		function __construct(){
			$this->cn = new Model();
		}

    public function DanhSachHoaDon()
    {
        $sql = "SELECT hoadon.*, dichvu.DonGia, dichvu.TenDV as TenDichVu, nhanvien.HoTen as TenNhanVien, khachhang.IdKhachHang, khachhang.TenKhachHang, phongHat.IdPhongHat, phonghat.TenPhongHat FROM hoadon 
        INNER JOIN dichvu ON hoadon.IdDichVu = dichvu.IdDichVu
        INNER JOIN nhanvien ON hoadon.IdNhanVien = nhanvien.IdNhanVien
        INNER JOIN khachhang ON hoadon.IdKhachHang = khachhang.IdKhachHang
        INNER JOIN phonghat ON hoadon.IdPhongHat = phonghat.IdPhongHat
        ORDER BY created_at ASC
        ";

        return $this->cn->FetchAll($sql); 
    }
    public function ThemHoaDon($IdHoaDon, $IdDichVu, $IdKhachHang, $IdNhanVien, $IdPhongHat, $SoLuong)
	  {
        $now = date("Y-m-d H:i:s");
        $sql = "INSERT INTO hoadon VALUES($IdHoaDon, $IdDichVu, $IdKhachHang, $IdNhanVien, $IdPhongHat, $SoLuong,'$now', '$now', '$now')";

        $soluongdichvu = $this->cn->Fetch("SELECT * FROM dichvu")['SoLuong'];

        //nếu số lượng xuất lớn hơn số lượng tồn tại trong kho
        if($SoLuong > $soluongdichvu)
          return false;

        $status = $this->cn->ExecuteQuery($sql);

        //nếu thêm thành công, giảm số lượng dịch vụ
        if($status)
          (new DichVu())->GiamSoLuong($IdDichVu, $SoLuong);
        return $status; 
	  }
    public function SuaHoaDon($IdHoaDon, $IdDichVu, $IdKhachHang, $IdNhanVien, $IdPhongHat, $SoLuong)
	  {
        $now = date("Y-m-d H:i:s");

        $sql_tim_hd = "SELECT * FROM hoadon WHERE IdHoaDon = $IdHoaDon";

        $hoadoncu = $this->cn->Fetch($sql_tim_hd);
        $soluongcu = $hoadoncu['SoLuong'];

        $sql = "UPDATE hoadon SET IdDichVu = $IdDichVu, IdKhachHang = $IdKhachHang, IdNhanVien = $IdNhanVien, IdPhongHat = $IdPhongHat, SoLuong = $SoLuong, updated_at='$now' WHERE IdHoaDon = $IdHoaDon";

        //Nếu tăng số lượng xuất
        if($SoLuong > $soluongcu){

          $SoLuongThem = $SoLuong - $soluongcu;

          $soluongdichvu = $this->cn->Fetch("SELECT * FROM dichvu")['SoLuong'];

          //nếu số lượng xuất lớn hơn số lượng tồn tại trong kho
          if($SoLuongThem > $soluongdichvu)
            return false;
        }

        $status = $this->cn->ExecuteQuery($sql);

        if($status){

          (new DichVu())->TangSoLuong($IdDichVu, $soluongcu);

          (new DichVu())->GiamSoLuong($IdDichVu, $SoLuong);
        }
        return $status; 
	  }
    public function TimKiem($IdHoaDon, $IdDichVu, $IdKhachHang, $IdNhanVien, $IdPhongHat)
    {
        $maHoaDon = trim($IdHoaDon) == ''? '" "' : $IdHoaDon;

        $timdichvu = trim($IdDichVu) == ''? '' : "OR dichvu.IdDichVu = $IdDichVu";
        $timkhachhang = trim($IdKhachHang) == ''? '' : "OR khachhang.IdKhachHang = $IdKhachHang";
        $timnhanvien = trim($IdNhanVien) == ''? '' : "OR nhanvien.IdNhanVien = $IdNhanVien";
        $timphonghat = trim($IdPhongHat) == ''? '' : "OR phonghat.IdPhongHat = $IdPhongHat";

        $sql = "SELECT hoadon.*, dichvu.IdDichVu, dichvu.DonGia, dichvu.TenDV as TenDichVu, nhanvien.HoTen as TenNhanVien, khachhang.IdKhachHang, khachhang.TenKhachHang, phongHat.IdPhongHat, phonghat.TenPhongHat FROM hoadon 
        INNER JOIN dichvu ON hoadon.IdDichVu = dichvu.IdDichVu
        INNER JOIN nhanvien ON hoadon.IdNhanVien = nhanvien.IdNhanVien
        INNER JOIN khachhang ON hoadon.IdKhachHang = khachhang.IdKhachHang
        INNER JOIN phonghat ON hoadon.IdPhongHat = phonghat.IdPhongHat
        WHERE IdHoaDon = $maHoaDon $timdichvu $timkhachhang $timnhanvien $timphonghat  
        ORDER BY created_at ASC
        ";
        return $this->cn->FetchAll($sql); 
    }
    function XoaHoaDon($ma)
		{
        $sql_tim_hd = "SELECT * FROM hoadon WHERE IdHoaDon = $ma";

        $hoadon = $this->cn->Fetch($sql_tim_hd);

			  $sql = "DELETE FROM hoadon WHERE IdHoaDon = $ma";

        $status = $this->cn->ExecuteQuery($sql);

        if($status){
          $IdDichVu = $hoadon['IdDichVu'];
          $SoLuong = $hoadon['SoLuong'];
          (new DichVu())->TangSoLuong($IdDichVu, $SoLuong);

        }
          
        return $status; 
		}

}