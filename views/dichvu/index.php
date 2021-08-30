<?php
    $_SESSION['error'] = '';
    
    //kiểm tra sửa
    if(isset($_POST["editForm"])){

        $form = $_POST["editForm"];

        $DichVu = new DichVu();

        $status = $DichVu->SuaDichVu($form["maDichVu"],
            $form["tenDichVu"],
            $form["moTa"],
            $form["donGia"],
            $form["soLuong"],
            $form["trangThai"]
        );
        //echo $status;
        if(!$status){
            $_SESSION['error'] = '<div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-ban" aria-hidden="true"></i>Lỗi khi truy vấn</h4>
            <p>kiểm tra lại mã bản ghi</p>
            </div>';
        }
    }

    //kiểm tra xóa
    if(isset($_POST["deleteForm"])){

        $maDichVu = $_POST["deleteForm"]["maDichVu"];

        $DichVu = new DichVu();

        $status = $DichVu->XoaDichVu($maDichVu);

        if(!$status){
            $_SESSION['error'] = '<div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-ban" aria-hidden="true"></i>Lỗi khi truy vấn</h4>
            <p>kiểm tra lại liên kết với hóa đơn</p>
            </div>';
        }
    }

    //Kiểm tra tìm kiếm
    if(isset($_REQUEST["searchForm"])){

        $form = $_REQUEST["searchForm"];

        $DichVu = new DichVu();
        $DichVus = $DichVu->TimKiem($form['maDichVu'], $form['tenDichVu'], $form['trangThai']);
    }
    else{
        $DichVu = new DichVu();
        $DichVus = $DichVu->DanhSachDichVu();
    }

    $name = 'Dịch vụ';
    $_SESSION['error'] = isset($_SESSION['error']) ? $_SESSION['error'] : '';
    //$name_upper = 'Dịch vụ';
?>
<div class="content-wrapper" id="pjax-container">
  <style type="text/css"> .w-100{width:100%;} .h-100{height:100%;} .h-400px{height:400px;} .fs-12{ font-size:12px; } .fs-16{ font-size:16px; } .p-0{padding:0;} .d-flex{display:flex;} .d-initial{display:initial;} .bootstrap-switch-handle-on, .bootstrap-switch-handle-off{ white-space: nowrap; } body{ font-family:system-ui; } @keyframes scale-1-3 { from {transform: scale(1);opacity:0.8;} to {transform: scale(1.3);opacity:1;} }  .column-selector { margin-right: 10px; } .column-selector .dropdown-menu { padding: 10px; height: auto; max-height: 500px; overflow-x: hidden; } .column-selector .dropdown-menu ul { padding: 0; } .column-selector .dropdown-menu ul li { margin: 0; } .column-selector .dropdown-menu label { width: 100%; padding: 3px; } </style>
  <div id="app">
    <section class="content-header">
      <h1>
      <?php echo $name;?>
        <small>Danh sách</small>
      </h1>
      <!-- breadcrumb start -->
      <!-- breadcrumb end -->
    </section>
    <section class="content">
        <?php echo $_SESSION['error']; ?>
      <div class="row">
        <div class="col-md-12">
          <div class="box grid-box">
            <div class="box-header with-border">
              <div class="pull-right">
                <div class="btn-group pull-right grid-create-btn" style="margin-right: 10px">
                <a href="index.php?controller=dichvu&action=add" class="btn btn-sm btn-success" title="Thêm mới">
                        <i class="fa fa-plus"></i><span class="hidden-xs">  Thêm mới</span>
                    </a>
                </div>
              </div>
              <div class="pull-left">
                <div class="btn-group grid-select-all-btn" style="display:none;margin-right: 5px;">
                  <a class="btn btn-sm btn-default hidden-xs"><span class="selected"></span></a>
                  <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown">
                  <span class="caret"></span>
                  <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="javascript:void(0);" class="grid-batch-action-611dfa7a0ff872598">Xóa</a></li>
                    <!-- ifisHoldSelectAllCheckbox  -->
                  </ul>
                </div>
                <div class="btn-group" style="margin-right: 5px" data-toggle="buttons">
                  <label class="btn btn-sm btn-dropbox 611dfa7a100ec-filter-btn active" title="Lọc">
                  <input type="checkbox"><i class="fa fa-filter"></i><span class="hidden-xs">  Lọc</span>
                  </label>
                </div>
              </div>
            </div>
            <div class="box-header with-border  filter-box" id="filter-box">
              <form action="" class="form-horizontal" method="post">
                <div class="row">
                  <div class="col-md-12">
                    <div class="box-body">
                      <div class="fields-group">
                        <div class="form-group">
                          <label class="col-sm-2 control-label"> Trạng thái</label>
                          <div class="col-sm-8">
                            <select class="form-control type" name="searchForm[trangThai]" style="width: 100%;">
                              <option <?php if(!isset($_POST["searchForm"]["trangThai"])) echo 'selected';?>value="1">Hoạt động</option>

                              <option <?php if(isset($_POST["searchForm"]["trangThai"]) && $_POST["searchForm"]["trangThai"] == 0) echo 'selected';?> value="0">Không hoạt động</option>

                              <option value="">Không chọn</option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label"> Mã dịch vụ</label>
                          <div class="col-sm-8">
                            <div class="input-group input-group-sm">
                              <div class="input-group-addon">
                                <i class="fa fa-pencil"></i>
                              </div>
                              <input type="text" class="form-control name" name="searchForm[maDichVu]" value="<?php echo isset($_POST["searchForm"]) ? $_POST["searchForm"]["maDichVu"] : '' ?>">
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label"> Tên dịch vụ</label>
                          <div class="col-sm-8">
                            <div class="input-group input-group-sm">
                              <div class="input-group-addon">
                                <i class="fa fa-pencil"></i>
                              </div>
                              <input type="text" class="form-control name" name="searchForm[tenDichVu]" value="<?php echo isset($_POST["searchForm"]) ? $_POST["searchForm"]["tenDichVu"] : '' ?>">
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="col-md-2"></div>
                      <div class="col-md-8">
                        <div class="btn-group pull-left">
                          <button class="btn btn-info submit btn-sm"><i class="fa fa-search"></i>Tìm kiếm</button>
                        </div>
                        <div class="btn-group pull-left " style="margin-left: 10px;">
                          <a href="index.php?controller=dichvu&action=index" class="btn btn-default btn-sm"><i class="fa fa-undo"></i>  Reset tìm kiếm</a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </form>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover grid-table" id="grid-table611dfe8fce584">
                <thead>
                  <tr>
                    <th class="column-id">Mã DV<a class="fa fa-fw fa-sort" href="https://truyenthanh.org.vn/auth/users?_pjax=%23pjax-container&amp;_sort%5Bcolumn%5D=id&amp;_sort%5Btype%5D=desc" aria-hidden="true"></a></th>
                    <th class="column-name">Tên <?php echo $name;?></th>
                    <th class="column-roles">Mô tả</th>
                    <th class="column-areaId">Đơn giá (đồng)</th>
                    <th class="column-stream_key">Số lượng còn</th>
                    <th class="column-stream_key">Trạng thái</th>
                    <th class="column-__actions__">Hành động</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    foreach($DichVus as $item){
                        if($item['TrangThai'])
                            $tt = '<span class="label label-info">Hoạt động</span>';
                        else 
                            $tt = '<span class="label label-danger">Không hoạt động</span>';
                        echo '<tr>
                        <td class="column-id">
                        '.$item['IdDichVu'].'
                        </td>
                        <td class="column-username">
                        <span class="label label-success" style="font-size:12px;">'.$item['TenDV'].'</span>
                        </td>
                        <td class="column-name">
                        '.$item['Mota'].'
                        </td>
                        <td class="column-roles">
                        '.number_format($item['DonGia']).'
                        </td>
                        <td class="column-areaId" style="font-size:16px;">
                        '.number_format($item['SoLuong']).'
                        </td>
                        <td class="column-stream_key">
                        '.$tt.'
                        </td>
                        <td class="column-__actions__">
                          <div class="grid-dropdown-actions dropdown">
                            <a href="#" style="padding: 0 10px;" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                            </a>
                            <ul class="dropdown-menu" style="min-width: 70px !important;box-shadow: 0 2px 3px 0 rgba(0,0,0,.2);border-radius:0;left: -65px;top: 5px;">
                            <li>
                            <form method="post" action="index.php?controller='.$_REQUEST['controller'].'&action=edit">
                                <input hidden class="hidden" type="text" name="editForm[maDichVu]" value="'.$item['IdDichVu'].'">
                                <input hidden class="hidden" type="text" name="editForm[tenDichVu]" value="'.$item['TenDV'].'">
                                <input hidden class="hidden" type="text" name="editForm[moTa]" value="'.$item['Mota'].'">
                                <input hidden class="hidden" type="text" name="editForm[donGia]" value="'.$item['DonGia'].'">
                                <input hidden class="hidden" type="text" name="editForm[soLuong]" value="'.$item['SoLuong'].'">
                                <input hidden class="hidden" type="text" name="editForm[trangThai]" value="'.$item['TrangThai'].'">
                                <button style="background-color: transparent;width: 100%;border: none;" type="submit">Sửa</button>
                                </form>
                            </li>
                            <li>
                            <button class="btn active-modal" style="background-color: transparent;width: 100%;border: none;" type="submit" data-toggle="modal" data-target="#ModalDelete'.$item['IdDichVu'].'">Xóa</button>
                            </li>
                            </ul>
                          </div>
                        </td>
                      </tr>';
                    }
                  ?>

                </tbody>
              </table>
            </div>
            <?php
              foreach($DichVus as $item){
                echo '
                  <div class="modal" id="ModalDelete'.$item['IdDichVu'].'" tabindex="-1">
                  s="modal-content">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">Xác nhận xóa</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                      <p>Bạn có muốn xóa dịch vụ #'.$item['IdDichVu'].'</p>
                    </div>
                    <div class="modal-footer">
                      <form method="post" action="">
                          <input hidden class="hidden" type="text" name="deleteForm[maDichVu]" value="'.$item['IdDichVu'].'">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                          <button type="submit" class="btn btn-danger">Xóa</button>
                      </form>
                    </div>
                  </div>
                </div>
              </div>';
              }
            ?>
            <div class="box-footer clearfix">
              <!-- Hiển thị <b>1</b> đến <b>20</b> trong tổng số <b>207</b> bản ghi
              <ul class="pagination pagination-sm no-margin pull-right">
                <li class="page-item disabled"><span class="page-link">«</span></li>
                <li class="page-item active"><span class="page-link">1</span></li>
                <li class="page-item"><a class="page-link" href="https://truyenthanh.org.vn/programs?page=2">2</a></li>
                <li class="page-item"><a class="page-link" href="https://truyenthanh.org.vn/programs?page=3">3</a></li>
                <li class="page-item"><a class="page-link" href="https://truyenthanh.org.vn/programs?page=4">4</a></li>
                <li class="page-item"><a class="page-link" href="https://truyenthanh.org.vn/programs?page=2" rel="next">»</a></li>
              </ul> -->
              <!-- <label class="control-label pull-right" style="margin-right: 10px; font-weight: 100;">
                <small>Hiển thị</small> 
                <select class="input-sm grid-per-pager" name="per-page">
                  <option value="https://truyenthanh.org.vn/programs?per_page=10">10</option>
                  <option value="https://truyenthanh.org.vn/programs?per_page=20" selected>20</option>
                  <option value="https://truyenthanh.org.vn/programs?per_page=30">30</option>
                  <option value="https://truyenthanh.org.vn/programs?per_page=50">50</option>
                  <option value="https://truyenthanh.org.vn/programs?per_page=100">100</option>
                </select>
                <small>bản ghi</small>
              </label> -->
            </div>
            <!-- /.box-body -->
          </div>
        </div>
      </div>
    </section>
  </div>
</div>