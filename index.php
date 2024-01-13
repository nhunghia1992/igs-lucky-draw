<?php $cache = '241301'; ?>
<!DOCTYPE html>
<html lang="vi-VN">
<head>
	<meta charset="utf-8"/>
	<meta content="width=device-width, initial-scale=1" name="viewport"/>
	<link href="http://gmpg.org/xfn/11" rel="profile"/>
	<title>
		Lucky Draw - Ivy Global School
	</title>
	<meta content="vi_VN" property="og:locale"/>
	<meta content="article" property="og:type"/>
	<meta content="Lucky Draw - Ivy Global School" property="og:title"/>
	<meta content="Lucky Draw - Ivy Global School" property="og:description"/>
	<meta content="Ivy Global School" property="og:site_name"/>
	<meta content="assets/imgs/bg.jpg" property="og:image"/>
	<meta content="Lucky Draw - Ivy Global School" name="twitter:card"/>
	<meta content="Lucky Draw - Ivy Global School" name="twitter:description"/>
	<meta content="Lucky Draw - Ivy Global School" name="twitter:title"/>
	<meta content="assets/imgs/bg.jpg" name="twitter:image"/>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
	<link rel="shortcut icon" href="https://ivyglobalschool.org/media/0scluqrx/logo-favicon.ico" type="image/x-icon" />
	<link rel="icon" href="https://ivyglobalschool.org/media/0scluqrx/logo-favicon.ico" type="image/ico" />

	<link rel="stylesheet" type="text/css" href="assets/css/main.css?v=<?php echo $cache; ?>" />
</head>
<body>
	<div class="container wrapper">
		<div class="row">
			<div class="col">
				<div class="prize-block" data-region="12" data-img="assets/imgs/prize-1.png?v=<?php echo $cache; ?>">
					<img class="img-fluid prize-img" src="assets/imgs/prize-1.png?v=<?php echo $cache; ?>" />
					<div class="prize-id text-center fw-bold mt-3 lead"></div>
					<div class="prize-name text-center fw-bold"></div>
					<div class="prize-phone text-center fw-bold"></div>
				</div>
			</div>
			<div class="col">
				<div class="prize-block" data-region="12" data-img="assets/imgs/prize-2.png?v=<?php echo $cache; ?>">
					<img class="img-fluid prize-img" src="assets/imgs/prize-2.png?v=<?php echo $cache; ?>" />
					<div class="prize-id text-center fw-bold mt-3 lead"></div>
					<div class="prize-name text-center fw-bold"></div>
					<div class="prize-phone text-center fw-bold"></div>
				</div>
			</div>
			<div class="col">
				<div class="prize-block" data-region="3" data-img="assets/imgs/prize-3.png?v=<?php echo $cache; ?>">
					<img class="img-fluid prize-img" src="assets/imgs/prize-3.png?v=<?php echo $cache; ?>" />
					<div class="prize-id text-center fw-bold mt-3 lead"></div>
					<div class="prize-name text-center fw-bold"></div>
					<div class="prize-phone text-center fw-bold"></div>
				</div>
			</div>
		</div>
		<div class="row mt-3">
			<div class="col actions text-center">
				<button class="btn btn-primary" id="show_list" data-bs-target="#winner_list" data-bs-toggle="modal">Xem danh sách</button>
				<button class="btn btn-success" id="start_random" data-bs-target="#winner_list" data-bs-toggle="modal">Quay số</button>
			</div>
		</div>
	</div>

	<audio src="assets/bgm.mp3" type="audio/mpeg" id="bgm">
		Your browser does not support the audio element.
	</audio>

	<div id="list_control">
		<button type="button" class="btn btn-danger" id="clear_list">
			Xóa danh sách
		</button>
	</div>

	<!-- Modal -->
	<div class="modal fade" id="winner_list" tabindex="-1" aria-labelledby="winner_list_label" aria-hidden="true">
		<div class="modal-dialog modal-fullscreen">
			<div class="modal-content">

				<div class="modal-body">
					<!--<button class="btn btn-light" id="toggle_size">Small</button>-->
					<div class="prize-blocks">
						<?php

						$api_url = 'https://sheets.googleapis.com/v4/spreadsheets/';
						$sheet_name = urlencode('Danh sách');
						$param = "/values/$sheet_name!A2:J?key=";
						$sheet_id = '1mzYCcy_VCzOeMPzdodfgIhyUEXmqr3izFu6VYHbpgv8';
						$api_key = 'AIzaSyCLFuNJC0xurqzXY2r1WoQcIDC3hHyR4uI';
						$json = file_get_contents($api_url.$sheet_id.$param.$api_key);
						$data_arr = json_decode($json)->values;

						foreach ($data_arr as $key => $data) {
							$id = $data[8] ?? '';
							if (!$id) continue;

							$id_display = $id;
							$id_display = strlen($id_display) < 2 ? '0'.$id_display : $id_display;
							$id_display = strlen($id_display) < 3 ? '0'.$id_display : $id_display;

							$name = $data[3] ?? '';
							$phone = $data[4] ?? '';
							if ($phone) $phone = '******'.substr($phone, -4);

							$region = $data[9] ?? '';

							$tooltip = [];
							if ($name) $tooltip[] = $name;
							if ($phone) $tooltip[] = $phone;
							$tooltip_html = implode('<br/>', $tooltip);

							echo "<span type='button' class='block' data-id='$id' data-region='$region' data-bs-toggle='tooltip' data-bs-html='true' data-name='$name' data-phone='$phone' title='$tooltip_html'>$id_display</span>";
						}

						?>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn close btn-secondary" data-bs-dismiss="modal">Quay về</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Dialog -->
	<div id="dialog" class="dialog">
		<div class="dialog-content">
			<img class="dialog-img img-fluid" src="assets/imgs/400.svg" />
			<div class="p-3 text-center fw-bold">
				<p class="dialog-id lead fw-bold"></p>
				<p class="dialog-name"></p>
				<p class="dialog-phone"></p>
				<button class="btn btn-secondary" onclick="closeDialog()">Đóng</button>
			</div>
		</div>
	</div>

	<script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>

	<script type="text/javascript" src="assets/js/main.js?v=<?php echo $cache; ?>"></script>
</body>
</html>