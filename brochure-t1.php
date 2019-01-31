<?php
	include("IsValid.class.php");
	// include("AccountsTable.class.php");
	//$all_features = array_merge($basic_info[APPEND_TRUE], $interior_features[APPEND_TRUE], $exterior_features[APPEND_TRUE]);
	$all_features = unserialize(file_get_contents("all_features.serialized"));
	$myrow = unserialize(file_get_contents("myrow.serialized"));
	$primary_agent = unserialize(file_get_contents("primary_agent.serialized"));
	$primary_photo_url = "https://5fef0e83e88e7201d5ac-03d894bd6aa9e18271441adec0a042f6.ssl.cf5.rackcdn.com/1041/77/LPHD0/10410677/10410677-jxR7bJ7RhmY7QJyR.jpg";
	$display_address = "123 Harborton Road";
	$profile_photo = "https://www.nystatemls.com/images/ACTIMG/A176/55676/p55676.jpg";
	$display_price = "$235,000";
	$status_text = "Active for Sale";
?>
<table cellpadding="0" cellspacing="0" border="0" style="max-width: 680px; border: 0px solid #383838;">
<tr>
	<td colspan="2">
		<div data-editable="background-color" style="padding: 10px;text-align: left; font-size: 18pt; font-weight: bold; color: #333; margin: 0 0 10px 0;">
			<?= $display_address.', '.$myrow['city'].', '.$myrow['state'].' '.$myrow['zip']; ?>
			<span style="float: right; font-size: 14pt;">#<?= $myrow['mlsid'] ?></span>
		</div>
	</td>
</tr>
<tr>
	<td>
		<div data-editable="image"  style="width: 455px; height: 350px; background-image:url(<?= $primary_photo_url ?>); background-size: cover">
		</div>
		<div data-editable="background-color" style="font-size: 22pt;margin: 5px; padding: 5px;"><?= $display_price ?><span style="float: right; font-size: 12pt;"><?= $status_text ?></span></div>
		<div data-editable="text background-color" style="height: 265px;">
			<?php
			$unbranded_desc = '';
			if (strlen($myrow['agent_desc']) > 1400)
			{
				$unbranded_desc = substr($myrow['agent_desc'], 0, 1400);
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
	<td data-editable="background-color" style="min-width: 220px; background-color: #EEE;">
		<div style="padding: 12px;">
			<?php if (!empty($all_features)) : ?>
			<div id="BasicDetails">
				<?php $num_elements = count($all_features); ?>
				<ul class="ListingValuesList">
				<?php for ($i = 0; $i < $num_elements; $i++) : ?>
					<li><?= $all_features[$i] ?></li>
				<?php endfor ?>
				</ul>
			</div>
			<?php endif // features ?>
		</div>
		
		<div style="text-align: center;">
			<?php if (IsValid::Url($profile_photo)) : ?>
				<div style="padding: 10px;">
				<div data-editable="image" style="width: 150px; height: 150px; border-radius: 100%; margin: auto; background-image:url(<?= $profile_photo ?>); background-size: cover">
				</div>
				<?php endif ?>
				<?php if (!empty($logo_url)) : ?>
				<div data-editable="image" style="max-width: 150px; max-height: 150px; background-image:url(<?= $logo_url ?>); background-size: cover">
				</div>
				<?php endif // logo url ?>

			<?php $agent_name = "Guy Rombaut"; ?>
			<div><strong style="font-size: 12pt;"><?= $agent_name; ?></strong></div>
			<div><span style="font-size: 13pt;"><?= $primary_agent['company']; ?></span></div>

			<?php if (!empty($primary_agent['bphone'])) { echo '<div>Office: ' . $primary_agent['bphone'] . '</div>'; } ?>
			<?php if (!empty($primary_agent['bcell'])) { echo '<div>Cell: ' . $primary_agent['bcell'] . '</div>'; } ?>

			<div><a href="mailto:<?= $primary_agent['accountemail']; ?>" title="Email Agent"><?= $primary_agent['accountemail']; ?></a></div>
		</div>
	</td>
</tr>
<tr>
	<td colspan="2" style="text-align: right; color: #888; font-size: 10pt; padding-top: 6px;">
		https://www.mystatemls.com/property/<?= $myrow['mlsid'] ?>/<br />
		My State MLS &middot; State Listings, Inc.
	</td>
</tr>
</table>

<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.js"></script>

<link rel="stylesheet" href="./plugin.css">
<script src="./plugin.js"></script>