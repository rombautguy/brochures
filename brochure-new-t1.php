<?php
	include("IsValid.class.php");
	$all_features = unserialize(file_get_contents("all_features.serialized"));
	$myrow = unserialize(file_get_contents("myrow.serialized"));
	$primary_agent = unserialize(file_get_contents("primary_agent.serialized"));
	$photo_data = unserialize(file_get_contents("photo_data.serialized"));
	$primary_photo_url = "https://5fef0e83e88e7201d5ac-03d894bd6aa9e18271441adec0a042f6.ssl.cf5.rackcdn.com/1041/77/LPHD0/10410677/10410677-jxR7bJ7RhmY7QJyR.jpg";
	$display_address = "123 Harborton Road";
	$profile_photo = "https://www.nystatemls.com/images/ACTIMG/A176/55676/p55676.jpg";
	$display_price = "$235,000";
	$status_text = "Active for Sale";
    $full_address = $display_address.'<br />'.$myrow['city'].', '.$myrow['state'].'<br />';
	$vital_features = $myrow['beds'].' Bedrooms<br />'.$myrow['above_area'].' Sq. Ft.<br />'.$myrow['full_baths'].' Bathrooms<br />';
?>
<style>
	.header {
		display: flex;
		justify-content: space-between;
		background-color: #28A7AD;
		color: white;
		font-size: 25px;
		font-weight: bold;
		padding: 10px;
		align-items: center;
	}
	.header-price {
		font-size: 40px;
	}
</style>
<div data-template-id="new-t1">
<table cellpadding="0" cellspacing="0" style="max-width: 680px; border: 0px solid #383838;">
	<tr>
		<td>
			<div id="header-color" class="header" data-editable="background-color">
				<div class="header-address">
					<span><?= $full_address ?></span>
				</div>
				<div class="header-price">
					<span>$<?= $myrow['price'] ?></span>
				</div>
				<div class="header-features">
					<span style="float: right;"> <?= $vital_features ?></span>
				</div>
			</div>
			<table cellpadding="2" cellspacing="0" >
				<tr>
					<td style="width: 510px;">
						<div id="primary_photo" data-editable="image" style="width: 510px; height: 300px; background-image:url(<?= $primary_photo_url ?>); background-size: cover">
						</div>
					</td>
					<td style="width: 170px;">
						<table cellpadding="2" cellspacing="0" >
								<tr>
										<td style="width: 170px;">
												<?php if (IsValid::Url($photo_data['p2']['url_small'])) : ?>
												<div id="small_photo1" data-editable="image" style="width: 170px; height: 150px; background-image:url(<?= $photo_data['p2']['url_small'] ?>); background-size: cover">
												</div>
												<?php endif ?>
										</td>
								</tr>
								<tr>
										<td style="width: 170px;">
												<?php if (IsValid::Url($photo_data['p3']['url_small'])) : ?>
												<div id="small_photo2" data-editable="image" style="width: 170px; height: 150px; background-image:url(<?= $photo_data['p3']['url_small'] ?>); background-size: cover">
												</div>
												<?php endif ?>
										</td>
								</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<table>
			<td style="width: 510px;">
				<div id="agent_desc" style="height: 260px; padding: 10px;" data-editable="text background-color font-color" data-character-limit=1200>
					<?php
					$unbranded_desc = '';
					if (strlen($myrow['agent_desc']) > 1200)
					{
						$unbranded_desc = substr($myrow['agent_desc'], 0, 1200);
						$dot_pos = strrpos($unbranded_desc, '.');
						$unbranded_desc = substr($unbranded_desc, 0, $dot_pos+1);
					}
					else
					{
						$unbranded_desc = $myrow['agent_desc'];
					}
					?>
					<?= $unbranded_desc ?>
				</div>
			</td>
			<td id="agent_detail" data-editable="background-color" style="width: 170px; background-color: #EEE;">
				<div style="text-align: center;">
					<?php if (IsValid::Url($profile_photo)) : ?>
					<div style="padding: 10px;">
					<div id="agent_photo" data-editable="image" style="margin-bottom: 20px;width: 150px; height: 150px; border-radius: 100%; background-image:url(<?= $profile_photo ?>); background-size: cover">
					</div>
					<?php endif ?>
					<?php if (!empty($logo_url)) : ?>
					<div id="agent_logo" data-editable="image" style="max-width: 150px; max-height: 150px; background-image:url(<?= $logo_url ?>); background-size: cover">
					</div>
					<?php endif // logo url ?>

					<?php $agent_name = "Guy Rombaut"; ?>
					<div><strong style="font-size: 12pt;"><?= $agent_name; ?></strong></div>
					<div><span style="font-size: 13pt;"><?= $primary_agent['company']; ?></span></div>

					<?php if (!empty($primary_agent['bphone'])) { echo '<div>' . $primary_agent['bphone'] . '</div>'; } ?>
					<?php if (!empty($primary_agent['bcell'])) { echo '<div>' . $primary_agent['bcell'] . '</div>'; } ?>
				</div>
			</td>
		</table>
	</tr>
	<tr>
		<td colspan="2" style="text-align: right; color: #888; font-size: 10pt; padding-top: 6px;">
			https://www.mystatemls.com/property/<?= $myrow['mlsid'] ?>/<br />
			My State MLS &middot; State Listings, Inc.
		</td>
	</tr>
</table>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.js"></script>

<link rel="stylesheet" href="css/colorpicker/colorpicker.css" type="text/css" />
<link rel="stylesheet" media="screen" type="text/css" href="css/colorpicker/layout.css" />
<script type="text/javascript" src="js/colorpicker/colorpicker.js"></script>
<script type="text/javascript" src="js/colorpicker/eye.js"></script>
<script type="text/javascript" src="js/colorpicker/utils.js"></script>

<link rel="stylesheet" href="./plugin.css">
<script src="./plugin.js"></script>