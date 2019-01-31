<?php
	include("IsValid.class.php");
	$all_features = unserialize(file_get_contents("all_features.serialized"));
	$myrow = unserialize(file_get_contents("myrow.serialized"));
	$primary_agent = unserialize(file_get_contents("primary_agent.serialized"));
    $photo_data = unserialize(file_get_contents("photo_data.serialized"));
    $agent_name = "Guy Rombaut";
	$primary_photo_url = "https://5fef0e83e88e7201d5ac-03d894bd6aa9e18271441adec0a042f6.ssl.cf5.rackcdn.com/1041/77/LPHD0/10410677/10410677-jxR7bJ7RhmY7QJyR.jpg";
	$display_address = "123 Harborton Road";
	$profile_photo = "https://www.nystatemls.com/images/ACTIMG/A176/55676/p55676.jpg";
	$display_price = "$235,000";
	$status_text = "Active for Sale";
    $full_address = $display_address.'<br />'.$myrow['city'].'<br />'.$myrow['state'].'<br />'.$myrow['zip'];
?>
<style>
    .header {
        display: flex;
        position: relative;
        color: white;
        font-size: 26px;
        font-weight: bold;
    }
    .header-left,
    .header-right {
        flex: 1;
    }
    .header-center {
        flex: 1.5;
        display: flex;
        flex-direction: column;
        text-align: center;
        margin: 0 5px;
    }
    .header-address {
        background-color: #222222;
    }
    .header-price {
        background-color: #222222;
        margin: 5px 0;
    }

    .content {
        display: flex;
        color: black;
    }
    .content-marketing {
        flex: 1;
        margin: 10px;
        line-height: 1.3;
    }
    .content-features {
        flex: 1;
        margin: 10px;
    }

    .footer {
        display: flex;
        background-color: #222222;
        color: white;
        justify-content: space-around;
        align-items: center;
        padding: 20px;
    }
    .agent-photo {
        border-radius: 50%;
        width: 100px;
        height: 100px;
        overflow: hidden;
    }
    .email-link {
        color: white;
    }
    
</style>
<table cellpadding="0" cellspacing="0" border="0" style="max-width: 680px; border: 0px solid #383838;">
<tr>
	<td>
		<div class="header">
            <div data-editable="image" class="header-left" style="background-image:url(<?= $photo_data['p6']['url_small'] ?>); background-size: cover"></div>
            <div class="header-center">
                <div data-editable="background-color" class="header-address">
                    <?= $full_address ?>
                </div>
                <div data-editable="background-color" class="header-price">
                    <?= $display_price ?>
                </div>
            </div>
            <div data-editable="image" class="header-right" style="background-image:url(<?= $photo_data['p7']['url_small'] ?>); background-size: cover"></div>
		</div>
        <table cellspacing="0" border="0">
			<tr>
                <td style="width: 170px;">
                    <table cellspacing="0" border="0">
                        <tr>
                            <td style="width: 170px;">
                                <?php if (IsValid::Url($photo_data['p4']['url_small'])) : ?>
                                <div data-editable="image" style="width: 170px; height: 150px; background-image:url(<?= $photo_data['p4']['url_small'] ?>); background-size: cover">
                                </div>
                                <?php endif ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 170px;">
                                <?php if (IsValid::Url($photo_data['p3']['url_small'])) : ?>
                                <div data-editable="image" style="width: 170px; height: 150px; background-image:url(<?= $photo_data['p3']['url_small'] ?>); background-size: cover">
                                </div>
                                <?php endif ?>
                            </td>
                        </tr>
                    </table>
				</td>
				<td style="width: 340px;">
                    <div data-editable="image" style="width: 340px; height: 300px; background-image:url(<?= $primary_photo_url ?>); background-size: cover">
                    </div>
				</td>
				<td style="width: 170px;">
                    <table cellspacing="0" border="0">
                        <tr>
                            <td style="width: 170px;">
                                <?php if (IsValid::Url($photo_data['p3']['url_small'])) : ?>
                                <div data-editable="image" style="width: 170px; height: 150px; background-image:url(<?= $photo_data['p3']['url_small'] ?>); background-size: cover">
                                </div>
                                <?php endif ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 170px;">
                                <?php if (IsValid::Url($photo_data['p2']['url_small'])) : ?>
                                <div data-editable="image" style="width: 170px; height: 150px; background-image:url(<?= $photo_data['p2']['url_small'] ?>); background-size: cover">
                                </div>
                                <?php endif ?>
                            </td>
                        </tr>
                    </table>
				</td>
			</tr>
		</table>
		<div data-editable="background-color" class="content">
            <div data-editable="text" class="content-marketing">
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
                <?= $unbranded_desc ?>
            </div>
            <div class="content-features">
                <?php if (!empty($all_features)) : ?>
                    <?php $num_elements = count($all_features); ?>
                    <table id="BasicDetails" cellpadding="4" cellspacing="0" border="0" style="margin: 0 auto;">
                        <tr>
                        <?php for ($i = 1; $i <= $num_elements; $i++) : ?>
                            <td style="width: 160px;">&#9679; <?= $all_features[$i-1] ?></td>
                            <?php if ((($i % 2) == 0) && ($i != $num_elements)) : ?>
                            </tr><tr>
                            <?php endif ?>
                        <?php endfor ?>
                        </tr>
                    </table>
                <?php endif // basic ?>
            </div>
        </div>
	</td>
</tr>

<tr>
	<td data-editable="background-color" class="footer">
        <?php if (IsValid::Url($profile_photo)) : ?>
        <div data-editable="image" style="width: 100px; height: 100px; border-radius: 100%; background-image:url(<?= $profile_photo ?>); background-size: cover">
        </div>
        <?php endif ?>
        <div class="agent-info">
            <div><strong style="font-size: 12pt;"><?= $agent_name; ?></strong></div>
            <div><span style="font-size: 13pt;"><?= $primary_agent['company']; ?></span></div>
        </div>
        <div class="agent-phone">
        <img src="https://img.icons8.com/color/48/000000/phone-disconnected.png">
            <?php if (!empty($primary_agent['bcell'])) { echo '<div>' . $primary_agent['bcell'] . '</div>'; } ?>
        </div>
        <div class="agent-email">
            <img src="https://img.icons8.com/color/48/000000/secured-letter.png">
            <div><a class="email-link" href="mailto:<?= $primary_agent['accountemail']; ?>" title="Email Agent"><?= $primary_agent['accountemail']; ?></a></div>
        </div>
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