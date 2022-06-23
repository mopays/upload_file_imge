<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
        <title>Basic Upload pdf file PHP PDO by devbanban.com 2021</title>
        <!-- sweet alert  -->
        <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
    </head>
    <body>
        <div class="container">
            <div class="row">
              <div class="col-md-1"></div>
                <div class="col-md-10"> <br>
                    <h3>PHP PDO Basic Upload PDF File</h3>
                    <form action="" method="post" enctype="multipart/form-data">
                        <input type="text" name="doc_name" required class="form-control" placeholder="ชื่อเอกสาร"> <br>
                         <font color="red">*อัพโหลดได้เฉพาะ .pdf เท่านั้น </font>
                        <input type="file" name="doc_file"    class="form-control" accept="application/pdf"> <br>
                        <font color="red">*อัพโหลดได้เฉพาะ รูป เท่านั้น </font>
                        <input type="file" name="doc_image"    class="form-control" accept="image/pdf, image/jepg, image/png"> <br>
                        
                        <button type="submit" name="upload_file" class="btn btn-primary">Upload pdf</button>
                        <button type="submit" name="upload_image" class="btn btn-primary">Upload image</button>
                    </form>
                    <h3>รายการเอกสาร </h3>
                    <a href="?delete"  class="btn btn-danger btn-sm"> ลบทั้งหมด </a>
                    <table class="table table-striped  table-hover table-responsive table-bordered">
                        <thead>
                            <tr>
                                <th width="5%">ลำดับ</th>
                                <th width="65%">ชื่อเอกสาร</th>
                                <th width="15%">รูป</th>
                                <th width="10%">เปิดดู</th>
                                <th width="10%">ลบ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            //คิวรี่ข้อมูลมาแสดงในตาราง
                            require_once 'connect.php';
                            $stmt = $db->prepare("SELECT* FROM tbl_file");
                            $stmt->execute();
                            $result = $stmt->fetchAll();
                            foreach($result as $row) {
                            ?>
                            <tr>
                                <td><?= $row['id'];?></td>
                                <td><?= $row['name'];?></td>
                              
                                <td><?php
                                        if($row['image'] ==''){
                                            echo '<p style="color:red;">no image</p>';
                                        }else{
                                        ?>
                                        <img style="width:50px;height:50px;"src="files/<?php echo $row['image'];?>" alt="">
                                        <?php
                                        }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                        if($row['file'] ==''){
                                            echo '<p style="color:red;">no files</p>';
                                        }else{
                                        ?>
                                        <a href="files/<?php echo $row['file'];?>" target="_blank" class="btn btn-info btn-sm"> เปิดดู </a>
                                        <?php
                                        }
                                        ?>
                                </td>
                                <td><a href="?delete_id=<?php echo $row['id'];?>"  class="btn btn-danger btn-sm"> ลบ </a></td>
                            <?php } ?>
                        </tbody>
                    </table>
                    <br>
                </div>
            </div>
        </div>
    </body>
</html>

<?php 
    if(isset($_POST['upload_image'])){
        $name = $_POST['doc_name'];

        $image = $_FILES['doc_image']['name'];
        $image_size =  $_FILES['doc_image']['size'];
        $image_tmp_name = $_FILES['doc_image']['tmp_name'];
        $image_folder = 'files/'.$image;



        $upload = $db->prepare("INSERT INTO `tbl_file`(name,image) VALUES (?,?)");
        $upload->execute([$name,$image]);

        if($image_size > 20000000){
            echo '<script>
            setTimeout(function() {
                swal({
                    title: "รูปใหญ่เกินไป",
                    type: "success"
                }, function() {
                    window.location = "index.php"; //หน้าที่ต้องการให้กระโดดไป
                });
            }, 500);
        </script>';
        }else{
            move_uploaded_file($image_tmp_name, $image_folder);
            echo '<script>
            setTimeout(function() {
                swal({
                    title: "upload success",
                    type: "success"
                }, function() {
                    window.location = "index.php"; //หน้าที่ต้องการให้กระโดดไป
                });
            }, 500);
        </script>';
        }
    }
?>
<?php 
if(isset($_REQUEST['delete'])){
    $delete = $db->prepare("DELETE FROM tbl_file ");
    $delete->execute(); 
    echo '<script>
            setTimeout(function() {
                swal({
                    title: "ลบไฟล์ทั้งหมดสำเร็จ",
                    type: "success"
                }, function() {
                    window.location = "index.php"; //หน้าที่ต้องการให้กระโดดไป
                });
            }, 500);
        </script>';
}
if(isset($_REQUEST['delete_id'])){
    $file_id = $_REQUEST['delete_id'];
    $delete = $db->prepare("DELETE FROM tbl_file WHERE id = ?");
    $delete->execute([$file_id]);
    echo '<script>
                     setTimeout(function() {
                      swal({
                          title: "ลบไฟล์สำเร็จ",
                          type: "success"
                      }, function() {
                          window.location = "index.php"; //หน้าที่ต้องการให้กระโดดไป
                      });
                    }, 500);
                </script>';
}
if (isset($_POST['doc_name'])) {
    require_once 'connect.php';
     //สร้างตัวแปรวันที่เพื่อเอาไปตั้งชื่อไฟล์ใหม่
    $date1 = date("Ymd_His");
    //สร้างตัวแปรสุ่มตัวเลขเพื่อเอาไปตั้งชื่อไฟล์ที่อัพโหลดไม่ให้ชื่อไฟล์ซ้ำกัน
    $numrand = (mt_rand());
    $doc_file = (isset($_POST['doc_file']) ? $_POST['doc_file'] : '');
    $upload=$_FILES['doc_file']['name'];

    //มีการอัพโหลดไฟล์
    if($upload !='') {
    //ตัดขื่อเอาเฉพาะนามสกุล
    $typefile = strrchr($_FILES['doc_file']['name'],".");

    //สร้างเงื่อนไขตรวจสอบนามสกุลของไฟล์ที่อัพโหลดเข้ามา
    if($typefile =='.pdf'){

    //โฟลเดอร์ที่เก็บไฟล์ **สร้างไฟล์ index.php หรือ index.html (ไม่ต้องมี code) ไว้ในโฟลเดอร์ด้วยนะครับจะได้ป้องกันการเข้าถึงทุกไฟล์ในโฟลเดอร์
    $path="files/";
    //ตั้งชื่อไฟล์ใหม่เป็น สุ่มตัวเลข+วันที่
    $newname = 'doc_'.$numrand.$date1.$typefile;
    $path_copy=$path.$newname;
    //คัดลอกไฟล์ไปยังโฟลเดอร์
    move_uploaded_file($_FILES['doc_file']['tmp_name'],$path_copy); 

     //ประกาศตัวแปรรับค่าจากฟอร์ม
    $doc_name = $_POST['doc_name'];
    
    //sql insert
    $stmt = $db->prepare("INSERT INTO tbl_file (name,file)
    VALUES (:doc_name, '$newname')");
    $stmt->bindParam(':doc_name', $doc_name, PDO::PARAM_STR);
    $result = $stmt->execute();
    $db = null; //close connect db
    //เงื่อนไขตรวจสอบการเพิ่มข้อมูล
            if($result){
                echo '<script>
                     setTimeout(function() {
                      swal({
                          title: "อัพโหลดไฟล์เอกสารสำเร็จ",
                          type: "success"
                      }, function() {
                          window.location = "index.php"; //หน้าที่ต้องการให้กระโดดไป
                      });
                    }, 1000);
                </script>';
            }else{
               echo '<script>
                     setTimeout(function() {
                      swal({
                          title: "เกิดข้อผิดพลาด",
                          type: "error"
                      }, function() {
                          window.location = "index.php"; //หน้าที่ต้องการให้กระโดดไป
                      });
                    }, 1000);
                </script>';
            } //else ของ if result

        
        }else{ //ถ้าไฟล์ที่อัพโหลดไม่ตรงตามที่กำหนด
            echo '<script>
                         setTimeout(function() {
                          swal({
                              title: "คุณอัพโหลดไฟล์ไม่ถูกต้อง",
                              type: "error"
                          }, function() {
                              window.location = "index.php"; //หน้าที่ต้องการให้กระโดดไป
                          });
                        }, 1000);
                    </script>';
        } //else ของเช็คนามสกุลไฟล์
   
    } // if($upload !='') {

    } //isset
?>