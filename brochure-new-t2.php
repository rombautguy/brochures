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
    $full_address = $display_address.', '.$myrow['city'].', '.$myrow['state'];
    $vital_features = $myrow['beds'].' Bed, '.$myrow['above_area'].' Sq. Ft., '.$myrow['full_baths'].' Bath<br />';
?>
<style>
    .header {
        position: relative;
        height: 100px;
        background-image: linear-gradient(to bottom right, white, #28A7AD);
        color: white;
        font-size: 26px;
        font-weight: bold;
    }
    
    .address-rect {
        position: absolute;
        top: 0;
        left: 0;
        height: 0;
        width: 400px;
        border-top: 100px solid white;
        border-right: 25px solid transparent;
    }
    .address-txt {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 350px;
        padding: 20px;
        color: black;
    }
    .txt-price {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        padding: 20px;
        right: 0
    }
    .marketing {
        width: 680px;
        height: 400px;
        overflow: hidden;
        position: relative;
        margin: 3px;
    }
    .marketing-rect {
        position: absolute;
        top: 0;
        left: 0;
        height: 0;
        width: 295px;
        border-top: 400px solid #222222;
        border-right: 102px solid transparent;
    }
    .marketing-txt {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 300px;
        padding: 20px;
        color: white;
        line-height: 1.4;
    }
    .vital-features {
        font-size: 20px;
        font-weight: bold;
        padding-bottom: 10px;
    }
    .agent {
        width: 680px;
        height: 300px;
        overflow: hidden;
        position: relative;
        margin: 3px;
    }
    .agent-rect {
        position: absolute;
        top: 0;
        right: 0;
        height: 0;
        width: 295px;
        border-top: 300px solid white;
        border-left: 102px solid transparent;
    }
    .agent-container {
        position: absolute;
        right: 10%;
        top: 50%;
        transform: translateY(-50%);
        text-align: center;
    }
</style>
<table cellpadding="0" cellspacing="0" border="0" style="max-width: 680px; border: 0px solid #383838;">
<tr>
	<td>
		<div class="header">
            <div class="address-rect"></div>
            <span class="address-txt"><?= $full_address ?></span>
            <span class="txt-price"><?= $display_price ?></span>
		</div>
		<div class="marketing" style="background: url(<?= $primary_photo_url ?>); background-size: cover;">
            <div class="marketing-rect"></div>
            <div class="marketing-txt">
                <?php
                $unbranded_desc = '';
                if (strlen($myrow['agent_desc']) > 500)
                {
                    $unbranded_desc = substr($myrow['agent_desc'], 0, 500);
                    $dot_pos = strrpos($unbranded_desc, '.');
                    $unbranded_desc = substr($unbranded_desc, 0, $dot_pos+1);
                }
                else
                {
                    $unbranded_desc = $myrow['agent_desc'];
                }
                ?>
                <div data-editable="text" class="vital-features"><?= $vital_features ?></div><br />
                <div data-editable="text"><?= $unbranded_desc ?></div>
            </div>
        </div>
		<div class="agent" style="background: url(<?= $photo_data['p4']['url_small'] ?>); background-size: cover;">
            <div class="agent-rect"></div>
            <div class="agent-container">
            <?php if (IsValid::Url($profile_photo)) : ?>
				<div style="padding: 10px;">
				<div data-editable="image" style="width: 150px; height: 150px; border-radius: 100%; background-image:url(<?= $profile_photo ?>); background-size: cover">
				</div>
				<?php endif ?>
				<?php if (!empty($logo_url)) : ?>
				<div data-editable="image" style="max-width: 150px; max-height: 150px; background-image:url(<?= $logo_url ?>); background-size: cover">
				</div>
				<?php endif // logo url ?>

                <?php $agent_name = "Guy Rombaut"; ?>
                <div><strong style="font-size: 12pt;"><?= $agent_name; ?></strong></div>
                <div><span style="font-size: 13pt;"><?= $primary_agent['company']; ?></span></div>

                <?php if (!empty($primary_agent['bphone'])) { echo '<div>' . $primary_agent['bphone'] . '</div>'; } ?>
                <?php if (!empty($primary_agent['bcell'])) { echo '<div>' . $primary_agent['bcell'] . '</div>'; } ?>
            </div>
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
<link href="color-picker.min.css" rel="stylesheet">
<script src="color-picker.min.js"></script>

<link rel="stylesheet" href="./plugin.css">
<script src="./plugin.js"></script>