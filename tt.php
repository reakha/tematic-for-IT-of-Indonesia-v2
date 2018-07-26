<?php
	include 'connect.php';
	include 'proses.php';
	// $i = isset($_POST['id'])?$_POST['id']:''; 
	// $j = isset($_POST['judul'])?$_POST['judul']:''; 
	// $a = isset($_POST['abstrak'])?$_POST['abstrak']:''; 
	// $t = isset($_POST['tema'])?$_POST['tema']:''; 
	// $n = isset($_POST['tambahtema']); 
	//$k = isset($_POST['tambahkeyword'])?$_POST['tambahkeyword']:''; 
?>
<!DOCTYPE html>
<html>
<head>
	<title>Text</title>
	<link rel="stylesheet" type="text/css" href="Assets\css\font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="Assets\css\style.css">
	<link href="Assets/css/bootstrap.min.css" rel="stylesheet">
	<script src="Assets/js/jquery-3.2.1.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<style type="text/css">
		body{
			margin: 0;
			padding: 0;
		}

		div{
			margin: 50px 0px -70px 350px;	
			width: 50%;
			height: 50%;
			text-align: left;
			border-radius: 5px;
		    background-color: #f2f2f2;
		    padding: 20px;
		}
		.ab
		{
			text-align: center;
			font-size: 13px;
		}
		.sub
		{
			text-align: center;
		}
		input[type=text], select {
		    width: 100%;
		    padding: 12px 20px;
		    margin: 8px 0;
		    display: inline-block;
		    border: 1px solid #ccc;
		    border-radius: 4px;
		    box-sizing: border-box;
		    text-align: left;
		}

		.proses{
			width: 100%;
		    background-color: #4CAF50;
		    color: white;
		    padding: 5px 20px;
		    margin: 8px 0;
		    border: none;
		    border-radius: 4px;
		    cursor: pointer;
		}

		.submit{
		    width: 70%;
		    background-color: #4CAF50;
		    color: white;
		    padding: 14px 20px;
		    margin: 8px 0;
		    border: none;
		    border-radius: 4px;
		    cursor: pointer;
		}

		input[type=submit]:hover {
		    background-color: #45a049;
		}
				
		textarea
		{
			min-width: 95%;
			min-height: 30vh;
			resize: none;
			border-radius: 4px;
			padding: 12px 20px;
		}
		.dropbtn {
		    background-color: #4CAF50;
		    color: white;
		    padding: 16px;
		    font-size: 16px;
		    border: none;
		    cursor: pointer;
		}

		.dropdown {
		    position: relative;
		    display: inline-block;
		}

		.dropdown-content {
		    display: none;
		    position: absolute;
		    background-color: #f9f9f9;
		    min-width: 160px;
		    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
		    z-index: 1;
		}

		.dropdown-content a {
		    color: black;
		    padding: 12px 16px;
		    text-decoration: none;
		    display: block;
		}

		.dropdown-content a:hover {background-color: #f1f1f1}

		.dropdown:hover .dropdown-content {
		    display: block;
		}

		.dropdown:hover .dropbtn {
		    background-color: #3e8e41;
		}

	</style>
</head>
<body>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
		
<br>
<br>
		<div class="in">
			<label>Tambah Tema :</label>
			<input class="judul" 
				   type="text" 
				   name="tambahtema"
				   value="<?php $n; ?>"
				   placeholder="Masukkan nama tema">
		</div>
		<div class="sub">
			<input class="submit" type="submit" name="submittambahtema" value="Tambah" >
		</div>
				<?php

					if (isset($_POST['submittambahtema']))
					{
						$nama_t = $_POST['tambahtema'];
						//echo $nama_t;

							if(empty($nama_t))
							{
								echo '<script>alert("Nama tema belum diisi")</script>';
						    }
						    else 
						    { 
						 
						    	$result = $connect->query("INSERT INTO tema (nama_tema) VALUES('$nama_t')");
								if ($result) 
								{
									$connect->query("SELECT nama_tema FROM tema")->num_rows != $nama_t;
									echo '<script>alert("Penambahan tema sukses!")</script>';
								} 
								else 
								{
									echo '<script>alert("Penambahan tema gagal atau nama tema sudah ada")</script>';
								}
						    }
					}
				?>
<br>
<br>

	</form>
</body>
</html>