<?php  
session_start();

require 'config/config.php';
require 'funcs/conexion.php';
require 'funcs/funcs.php';
require 'funcs/files.php';

if (!validateSession()) {

	echo '<meta http-equiv="refresh" content="0; url='.SERVERURL.'index.php">';
	die();
} 

$index = "root";

$filesUser = getFiles($index);
$foldersUser = getFolder($index);
$AllFiles = getAllFiles($index);
$AllFolders = getAllFolders($index);
$Stats = getStats();

if (isset($_POST['AddFile'])) {
		
	if ($_FILES) {
			
		$file = $_FILES;

		addFile($file, $index);
			
	}
}

if (isset($_POST['CreateFolder'])) {

	$nameFolder =  $mysqli->real_escape_string($_POST['folder']);
		
	createFolder($nameFolder, $index);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="google" content="notranslate">
	<meta name="format-detection" content="telephone=no"/>
	<meta name="theme-color" content="<?php echo THEMECOLOR ?>">
	<meta name="description" content="<?php echo APPDESCRIPTION ?>">
	<meta name="og:description" content="<?php echo APPDESCRIPTION ?>"/>
	<meta name="og:url" content="<?php echo SERVERURL ?>"/>
	<meta name="og:title" content="<?php echo APPTITLE ?>"/>
	<meta name="og:image" content="<?php echo SERVERURL ?>img/private_server.png"/>
	<!--	ICONS PAGE	-->
		<link id="favicon" rel="shortcut icon" href="<?php echo SERVERURL ?>favicon.svg" >

	<link rel="apple-touch-icon" sizes="194x194" href="<?php echo SERVERURL ?>img/apple-touch-icon.png" type="image/png"/>
	<!--    NORMALIZE.CSS v8.0.1    -->
	<link rel="stylesheet" href="<?php echo SERVERURL ?>css/normalize.css">
	<!--    CUSTOM CSS    -->
	<link rel="stylesheet" href="<?php echo SERVERURL ?>css/elements.css">
	<link rel="stylesheet" href="<?php echo SERVERURL ?>css/scrollbar.css">
	<link rel="stylesheet" href="<?php echo SERVERURL ?>css/home.css">
	<link rel="stylesheet" href="<?php echo SERVERURL ?>css/dashboard.css">
	<!--	ICONS fontawesome-free	-->
	<link rel="stylesheet" href="<?php echo SERVERURL ?>plugins/fontawesome-free/css/all.min.css">
	<!--    SCRIPT JS    --->
	<script src="<?php echo SERVERURL ?>js/script.js"></script>
	<title><?php echo TITLEHOME ?></title>
</head>
<body >

	<input type="checkbox" name="btnnavbar" id="btnnavbar">

	<aside class="navbar">
		<nav>
			<ul>
				<?php if ($_SESSION['id_tipo'] == 3) { ?>
					<li><a href="<?php echo SERVERURL; ?>dashboard.php"  class="navActive"><i class="fas fa-chart-line"></i></i><span>Dashboard</span></a></li>
					<li><a href="<?php echo SERVERURL; ?>home.php"><i class="far fa-file"></i><span>My files</span></a></li>
					<li><a href="<?php echo SERVERURL; ?>exit.php"><i class="fas fa-sign-out-alt"></i><span>Exit</span></a></li>
				<?php } ?>

				<?php if ($_SESSION['id_tipo'] == 1 || $_SESSION['id_tipo'] == 2) { ?>

				<li><a href="<?php echo SERVERURL; ?>dashboard.php" class="navActive" class="navActive"><i class="fas fa-chart-line"></i></i><span>Dashboard</span></a></li>
				<li><a href="<?php echo SERVERURL; ?>home.php"><i class="far fa-file"></i><span>My files</span></a></li>
				<li><a href="<?php echo SERVERURL; ?>user/account.php"><i class="far fa-user"></i><span>Account</span></a></li>
				<?php if ($_SESSION['id_tipo'] == 1) { ?>
				<li><a href="<?php echo SERVERURL; ?>register.php"><i class="fas fa-user-plus"></i><span>Register User</span></a></li>
				<li><a href="<?php echo SERVERURL; ?>user/users.php"><i class="fas fa-users"></i><span>Users</span></a></li>

				<?php } ?>
				<li class="settings"><a href="<?php echo SERVERURL; ?>user/setting.php"><i class="fas fa-cog"></i><span>Settings</span></a></li>
				<li><a href="<?php echo SERVERURL; ?>exit.php"><i class="fas fa-sign-out-alt"></i><span>Exit</span></a></li>

				<?php } ?>

			</ul>
		</nav>
	</aside>

	<input type="checkbox" name="btnuserImage" id="btnuserImage">
	
	<section class="header">
		<header><button onClick="history_back()"><i class="fas fa-arrow-left"></i></button>
   <label for="btnnavbar"><i class="fas fa-bars"></i></label><span>&nbsp;Records <strong>Buffet<i class="fas fa-lock"></i></strong></span></header>
		
		<div class="userImage">
			<label for="btnuserImage" id="labeluserImage">
				<?php echo subUser($_SESSION['name']) ; ?>
			</label>
			<img src="<?php echo SERVERURL.$_SESSION['image']?>">
			<div class="userImage-options">
				<p><a href="<?php echo SERVERURL; ?>user/account.php"><span><?php echo $_SESSION['email']; ?></span><i class="fas fa-envelope"></i></a></p>
				<p><a href="<?php echo SERVERURL; ?>user/setting.php"><span>Settings</span><i class="fas fa-cog"></i></a></p>
				<p><a href="<?php echo SERVERURL; ?>exit.php"><span>Exit</span><i class="fas fa-sign-out-alt"></i></a></p>
			</div>
		</div>
	</section>
	
    <main class="dash">
        <h2>Estadisticas recientes</h2>
        <section class="data">
            <?php 
            $i = -1;
            $list_names = ["Casos registrados", "Usuarios","Descargas","Casos Resueltos"];
            foreach ($Stats as $stat) { 
                $i=$i+1; ?>
                <article>
                    <h3>
                        <strong><?php echo $stat ?></strong>
                        <?php echo $list_names[$i] ?>
                    </h3>
                </article>
            <?php }?>
        </section>

        <div class="chart">
  <canvas id="myChart"></canvas>
</div>

<a href="fpdf/reporte.php" class="btn btn-success">Generar reporte<i class="fas fa-file-pdf"></i></a>
    </main>


	<script src="/js/chart.js"></script>

<script>
  const ctx = document.getElementById('myChart');

  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ["Casos registrados", "Usuarios","Descargas","Casos Resueltos"],
      datasets: [{
        label: '# of tasks',
		// backgroundColor:'#b12',
        data: [<?php foreach ($Stats as $val) {echo $val.",";} ?>],
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });

  function history_back() {
            window.history.back();
        } 

</script>
</div>
</body>
</html>