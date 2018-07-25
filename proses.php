<?php
	error_reporting(0);
	include 'connect.php';
	include 'stemming.php';
	$as = $_POST['abstrak'];
	$sw = $connect->query("SELECT stopword FROM kata_hubung");
	$kd = $connect->query("SELECT kata_dasar FROM kata_dasar");
	$kb = $connect->query("SELECT kata_berimbuhan FROM kata_dasar");
	$it = $connect->query("SELECT id_tema FROM tema");
	$nt = $connect->query("SELECT nama_tema FROM tema");
	
	$id_tema = array();
		foreach ($it as $key => $value)
		{
			foreach ($value as $key1 => $value1) 
			{
				$id_tema[] = $value1; 
			}
		}

	$nama_tema = array();
		foreach ($nt as $key => $value)
		{
			foreach ($value as $key1 => $value1) 
			{
				$nama_tema[] = $value1; 
			}
		}

	$idt_nt = array_combine($id_tema, $nama_tema);
								// echo "<pre>";
								// print_r($idt_nt);
								// echo "<pre>";


	$kata_hubung = array();
		foreach ($sw as $key => $value) 
		{
			foreach ($value as $key1 => $value1) 
			{
				$kata_hubung[] = $value1;
			}
		}

	$k_dasar = array();
		foreach ($kd as $key => $value) 
		{
			foreach ($value as $key1 => $value1) 
			{
				$k_dasar[] = $value1;
			}
		}
	$k_berimbuhan = array();
		foreach ($kb as $key => $value) 
		{
			foreach ($value as $key1 => $value1) 
			{
				$k_berimbuhan[] = $value1;
			}
		}
	$kata_dasar = array_combine($k_berimbuhan, $k_dasar);

	$at = array();
		for ($i=1; $i<=count($nama_tema); $i++) 
		{ 
			$at[$i] = $connect->query("SELECT keyword FROM keyword where id_tema = $i");
		}
		// echo "<pre>";
		// print_r($at);
		// echo "<pre>";

	foreach ($at as $key => $value) 
		{
								
			foreach ($value as $key1 => $value1) 
			{
				$abstrakT[] = $value1;
			}
		}
		// echo "<pre>";
		// print_r($abstrakT);
		// echo "<pre>";						
								
	//function NLP
	function t($as,$at,$kata_hubung,$kata_dasar)
	{
		//abstrak
		foreach ($as as $key => $value) 
		{
			$abstrakS[] = $value;
		}
		foreach ($at as $key => $value) 
		{
			foreach ($value as $key1 => $value1) 
			{
				//if(strlen($value1)>=4)
				//{
					$abstrakT[] = $value1;
				//}
			}
		}

		
		//parser
		foreach ($abstrakS as $key => $value) 
		{
			$parserS[] = strtolower(preg_replace('/[^a-zA-Z ]/', '',$value));
		}
		foreach ($abstrakT as $key => $value) 
		{
			$parserT[] = strtolower(preg_replace('/[^a-zA-Z ]/', '',$value));
		}

		//filter
		foreach ($parserS as $key => $value) 
		{
			$filterS[] = preg_replace('/\b('.implode('|',$kata_hubung).')\b/','',$value);
		}
		foreach ($parserT as $key => $value) 
		{
			$filterT[] = preg_replace('/\b('.implode('|',$kata_hubung).')\b/','',$value);
		}

		//stemming1
		foreach ($filterS as $key => $value) 
		{
			$stemmingSs[] = str_replace(array_keys($kata_dasar), array_values($kata_dasar), $value);
		}
		foreach ($filterT as $key => $value) 
		{
			$stemmingTt[] = str_replace(array_keys($kata_dasar), array_values($kata_dasar), $value);
		}

		//stemming2
		foreach ($stemmingSs as $key => $value) 
		{
			$stemmingS[] = preg_replace('/\b('.implode('|',$kata_dasar).')\b/','',$value);
		}
		foreach ($stemmingTt as $key => $value) 
		{
			$stemmingT[] = preg_replace('/\b('.implode('|',$kata_dasar).')\b/','',$value);
		}

		//per kata menjadi indek
		foreach ($stemmingS as $key => $value) 
		{
			$stmSSS[] = str_word_count($value, 1);
			$stmSS = $stmSSS[$key];
		}
		foreach ($stmSS  as $key => $value) 
		{
			$stmS[$key] = str_word_count($value, 1);
		}

		foreach ($stemmingT as $key => $value) 
		{
			$stmT[] = str_word_count($value, 1);
		}

		//rabin karp for text similarity
		foreach ($stemmingS as $key => $value) 
		{
			if (strlen($value)>=4) 
			{
				//kgram 4
				for($i = 0; $i < count($stmSS); $i++)
				{
					$kgramS[$i] = array();				    
						for($j = 0; $j < (strlen($stmSS[$i]) - 3); $j++)
						{	
							$kgramS[$i][] = substr($stmSS[$i], $j, 4);
						}
				}
				for($i = 0; $i < count($stemmingT); $i++)
				{
					$kgramT[$i] = array();				    
						for($j = 0; $j < (strlen($stemmingT[$i]) - 3); $j++)
						{	
							$kgramT[$i][] = substr($stemmingT[$i], $j, 4);
						}
				}

				//ascii indek 0
				$change_to_ascii_indek_0S = array();
				foreach ($kgramS as $key => $value) 
				{
					foreach ($value as $key1 => $value1) 
					{
						$change_to_ascii_indek_0S[$key][] = (ord($value1[0])*1000);
					}
				}
				$change_to_ascii_indek_0T = array();
				foreach ($kgramT as $key => $value) 
				{
					foreach ($value as $key1 => $value1) 
					{
						$change_to_ascii_indek_0T[$key][] = (ord($value1[0])*1000);
					}
				}

				//ascii indek 1
				$change_to_ascii_indek_1S = array();
				foreach ($kgramS as $key => $value) 
				{
					foreach ($value as $key1 => $value1) 
					{
						$change_to_ascii_indek_1S[$key][] = (ord($value1[1])*100);
					}
				}
				$change_to_ascii_indek_1T = array();
				foreach ($kgramT as $key => $value) 
				{
					foreach ($value as $key1 => $value1) 
					{
						$change_to_ascii_indek_1T[$key][] = (ord($value1[1])*100);
					}
				}

				//ascii indek 2
				$change_to_ascii_indek_2S = array();
				foreach ($kgramS as $key => $value) 
				{
					foreach ($value as $key1 => $value1) 
					{
						$change_to_ascii_indek_2S[$key][] = (ord($value1[2])*10);
					}
				}
				$change_to_ascii_indek_2T = array();
				foreach ($kgramT as $key => $value) 
				{
					foreach ($value as $key1 => $value1) 
					{
						$change_to_ascii_indek_2T[$key][] = (ord($value1[2])*10);
					}
				}

				//ascii indek 3
				$change_to_ascii_indek_3S =array();
				foreach ($kgramS as $key => $value) 
				{
					foreach ($value as $key1 => $value1) 
					{
						$change_to_ascii_indek_3S[$key][] = (ord($value1[3])*1);
					}
				}
				$change_to_ascii_indek_3T = array();
				foreach ($kgramT as $key => $value) 
				{
					foreach ($value as $key1 => $value1) 
					{
						$change_to_ascii_indek_3T[$key][] = (ord($value1[3])*1);
					}
				}

				//plus indek 0123
				$plus_all_indekS = array();
				for($i = 0; $i < count($stmSS); $i++)
				{
					for($j = 0; $j < (strlen($stmSS[$i]) -3); $j++) 
					{
				  		$plus_all_indekS[$i][$j] = $change_to_ascii_indek_0S[$i][$j] + 
				  								   $change_to_ascii_indek_1S[$i][$j] +
				  								   $change_to_ascii_indek_2S[$i][$j] +
												   $change_to_ascii_indek_3S[$i][$j];
				  	}
				}
				
				$plus_all_indekT = array();
				for($i = 0; $i < count($stemmingT); $i++)
				{
					for($j = 0; $j < (strlen($stemmingT[$i]) -3); $j++) 
					{
				  		$plus_all_indekT[$i][$j] = $change_to_ascii_indek_0T[$i][$j] + 
				  								   $change_to_ascii_indek_1T[$i][$j] +
				  								   $change_to_ascii_indek_2T[$i][$j] +
						                           $change_to_ascii_indek_3T[$i][$j];
						
				  	}
				}
		
				$sum_arrayS = array();
				for($i = 0; $i < count($stmSS); $i++)
				{
					for($j = 0; $j < (strlen($stmSS[$i]) -3); $j++) 
					{
						$sum_arrayS[$i] = count($plus_all_indekS[$i]);
					}
				}
				// echo "<pre>";
				// print_r($sum_arrayS);
				// echo "<pre>";

				$sum_arrayT = array();
				for($i = 0; $i < count($stemmingT); $i++)
				{
					for($j = 0; $j < (strlen($stemmingT[$i]) -3); $j++) 
					{
						$sum_arrayT[$i] = count($plus_all_indekT[$i]);
					}
				}
				// echo "<pre>";
				// print_r($sum_arrayT);
				// echo "<pre>";

				//echo "=====================================================================================";
				//same value
				$textSimilarity = array();
				foreach ($plus_all_indekT as $key => $value) 
				{
					for ($i=0; $i < count($plus_all_indekS); $i++) 
					{
							$textSimilarity[$key][] = floor(
							(2*count(array_intersect($value, $plus_all_indekS[$i]))) / ($sum_arrayT[$key]+count($plus_all_indekS[$i])) );
					}
				}
				// echo "<pre>";
				// print_r($textSimilarity);
				// echo "<pre>";
				return $textSimilarity;
			}
		}
	}
	

	function p($as,$at,$kata_hubung,$kata_dasar,$id_tema,$nama_tema)
	{
								
		foreach ($as as $key => $value) 
		{
				if (strlen($value)>=4) 
				{	
					foreach ($at as $key => $value) 
					{
						for ($i=1; $i<=count($nama_tema); $i++) 
						{
							$c[$i] = t($as,$at[$i],$kata_hubung,$kata_dasar);
						}
					}
						foreach ($c as $key => $value) 
						{
							if (empty($value)) 
							{
								$c[$key][] = 0;
							}
						}
								// echo "<pre>";
								// print_r($c);
								// echo "<pre>";				
						foreach ($c as $key => $value) 
						{
							foreach ($value as $key1 => $value1) 
							{
								$cc[$key][] = array_sum($value1);
							}
						}
								
							foreach ($cc as $key => $value) 
							{
								$ccc[$key] = array_sum($value);
							}
								// echo "<pre>";
								// print_r($ccc);
								// echo "<pre>";	
								if (array_sum($ccc) > 0) 
								{
									foreach ($ccc as $key => $value) 
									{
										$cccc[$key] = $value/array_sum($ccc);
									}							
										//mengubah $nama_tema menjadi key indek dari $cccc
										$nama = array_combine($nama_tema, $cccc);
										//mengubah $id_tema menjadi key indek dari $id
										$id = array_combine($id_tema, $nama);
										//sorting untuk descanding
										arsort($nama);
										arsort($id);
										// echo "<pre>";
										// print_r($nama);
										// print_r($id);
										// echo "<pre>";
										//array baru unutk menampung id_tema, nama_tema, hasil textSimilarity
										$dd = array();
										foreach ($id as $key => $value) 
										{
											if ($value > 0) 
											{
												$dd[$key] = $key;
											}
										}
										$ccccc = array();
										foreach ($nama as $key => $value) 
										{
											if ($value > 0) 
											{
												$ccccc[][$key] = $value;
											}
										}
										// echo "<pre>";
										// print_r($dd);
										// print_r($ccccc);
										// echo "<pre>";
										$cccccc = array_combine($dd, $ccccc);

										return $cccccc;

								}
								else
								{
									echo '<script>alert("Isi dengan benar, minimal 1 paragraf")</script>';
								}
				}
				else
				{
					echo '<script>alert("Isi dengan benar, minimal 1 paragraf")</script>';
				}
		}	
	}
	
	function sent_skripsi($id_skripsi, $judul, $abstrak, $id_tema, $connect)
	{
		$result = $connect->query("INSERT INTO skripsi (id_skripsi, judul_skripsi, abstrak_skripsi, id_tema) 
					VALUES('$id_skripsi','$judul','$abstrak','$id_tema')");
		// echo "<pre>";
		// print_r($id_skripsi);
		// echo "<br>";
		// print_r($judul);
		// echo "<br>";
		// print_r($abstrak);
		// echo "<br>";
		// print_r($id_tema);
		// print_r($result);
		// echo "<pre>";

		if ($result) 
		{
			$connect->query("SELECT id_skripsi FROM skripsi")->num_rows != $id_skripsi;
			echo '<script>alert("Pengiriman sukses!")</script>';
			//$id_skripsi = $judul = $abstrak = $id_tema = "";
		} 
		else 
		{
			echo '<script>alert("Pengiriman gagal atau id sudah mengirim")</script>';
		}
	}

	function tambahtema($nama_tema, $connect)
		{
			$result = $connect->query("ALTER TABLE tema AUTO_INCREMENT = 0;  INSERT INTO tema (nama_tema) 
						VALUES('$nama_t')");
			// echo "<pre>";
			// print_r($nama_tema);
			// echo "<pre>";

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

		// echo "<pre>";
		// print_r(p($as,$at,$kata_hubung,$kata_dasar,$id_tema,$nama_tema));
		// echo "<pre>";

		// Skripsi / karya ilmiah merupakan karangan ilmu pengetahuan yang menyajikan fakta dan ditulis menurut metodologi penulisan yang baik dan benar. Namun untuk mengetahui tema apa yang dibahas minimal harus membaca abstaks dari skripsi. Seiring dengan perkembangan teknologi informasi yang menawarkan kemudahan bagi tenaga kerja manusia dalah hal penyederhanaan pekerjaanya. Kesulitan dalam menentukan tema dalam skripsi bisa di bantu dengan aplikasi yang mampu menetukan tema berdasarkan abstraks. Untuk membuat aplikasi penentuan tematik skripsi dapat menggunakan algoritma string matching, salah satu algoritma string matching adalah algoritma Rabin-Karp dengan melakukan pencocokan string berdasarkan nilai hash pada teks dan nilai hash pada pattern.  Aplikasi dibangun berbasis web dengan menggunakan bahasa pemrograman PHP dan database MySQL. Kata Kunci : Skripsi, Abstrak, Tematik, String Matching, Rabin-Karp, Hash.

?>
