<?php
$sub_menu = '800100';
include_once('./_common.php');
include_once(G5_EDITOR_LIB);
include_once(EYOOM_PATH.'/common.php');
auth_check($auth[$sub_menu], "r");

$g5['title'] = '이윰환경설정';
include_once (G5_ADMIN_PATH.'/admin.head.php');

include './eyoom_theme.php';

$frm_submit = '
<div class="btn_confirm01 btn_confirm">
    <input type="submit" value="확인" class="btn_submit" accesskey="s">
    <a href="'.G5_URL.'">메인으로</a>
</div>
';
?>
<link rel="stylesheet" href="./css/eyoom_admin.css">
<form name="ftheme" action="./config_form_update.php" onsubmit="return ftheme_check(this)" method="post" enctype="MULTIPART/FORM-DATA">
<input type="hidden" name="mode" id="mode" value="skin">
<input type="hidden" name="theme" id="theme" value="<?php if($_theme) echo $_theme; else echo $theme;?>">
<input type="hidden" name="ref" id="ref" value="config_form.php">

<?php include './theme_manager.php'; // 테마 메니저 ?>

<?php if($_theme) {?>
<section id="anc_scf_info">
    <h2 class="h2_frm">[<b style='color:#f30;'><?php echo $_theme;?></b> 테마] 스킨설정 <span class='exp'>그누보드 스킨 선택시 그누보드 환경설정에서 스킨을 선택하거나 스킨파일에서 직접 설정하셔야 합니다.</span></h2>

    <div class="tbl_frm01 tbl_wrap">
        <table>
        <caption>스킨설정</caption>
        <colgroup>
            <col class="grid_4">
            <col>
            <col class="grid_4">
            <col>
        </colgroup>
        <tbody>
        <tr>
            <th scope="row"><label for="theme">아웃로그인 스킨</label></th>
            <td>
                <?php
                $arr = $eb->get_skin_dir('outlogin',EYOOM_THEME_PATH.'/'.$_theme.'/skin_bs');
				$checked['use_gnu_outlogin1'] = $_eyoom['use_gnu_outlogin'] == 'n' ? 'checked="checked"':'';
				$checked['use_gnu_outlogin2'] = $_eyoom['use_gnu_outlogin'] == 'y' ? 'checked="checked"':'';
				if($arr) {
					echo '<select name="outlogin_skin" id="outlogin_skin" required class="required">';
					for ($i=0; $i<count($arr); $i++) {
						if ($i == 0) echo "<option value=\"\">선택</option>";
						echo "<option value=\"".$arr[$i]."\"".get_selected($_eyoom['outlogin_skin'], $arr[$i]).">".$arr[$i]."</option>\n";
					}
					echo '</select>';
					echo '<label for="use_gnu_outlogin1"><input type="radio" name="use_gnu_outlogin" id="use_gnu_outlogin1" value="n" '.$checked['use_gnu_outlogin1'].'> 이윰빌더 스킨</label>';
					echo '<label for="use_gnu_outlogin2"><input type="radio" name="use_gnu_outlogin" id="use_gnu_outlogin2" value="y" '.$checked['use_gnu_outlogin2'].'> 그누보드 스킨</label>';
				} else {
					echo "현재 테마에는 아웃로그인 스킨이 존재하지 않습니다.";
				}
				unset($arr);
                ?>
            </td>
            <th scope="row"><label for="de_admin_company_saupja_no">현재접속자 스킨</label></th>
            <td>
                <?php
                $arr = $eb->get_skin_dir('connect',EYOOM_THEME_PATH.'/'.$_theme.'/skin_bs');
				$checked['use_gnu_connect1'] = $_eyoom['use_gnu_connect'] == 'n' ? 'checked="checked"':'';
				$checked['use_gnu_connect2'] = $_eyoom['use_gnu_connect'] == 'y' ? 'checked="checked"':'';
				if($arr) {
					echo '<select name="connect_skin" id="connect_skin" required class="required">';
					for ($i=0; $i<count($arr); $i++) {
						if ($i == 0) echo "<option value=\"\">선택</option>";
						echo "<option value=\"".$arr[$i]."\"".get_selected($_eyoom['connect_skin'], $arr[$i]).">".$arr[$i]."</option>\n";
					}
					echo '</select>';
					echo '<label for="use_gnu_connect1"><input type="radio" name="use_gnu_connect" id="use_gnu_connect1" value="n" '.$checked['use_gnu_connect1'].'> 이윰빌더 스킨</label>';
					echo '<label for="use_gnu_connect2"><input type="radio" name="use_gnu_connect" id="use_gnu_connect2" value="y" '.$checked['use_gnu_connect2'].'> 그누보드 스킨</label>';
				} else {
					echo "현재 테마에는 현재접속자 스킨이 존재하지 않습니다.";
				}
				unset($arr);
                ?>
            </td>
        </tr>
		<tr>
			<th scope="row"><label for="de_admin_company_saupja_no">인기검색어 스킨</label></th>
            <td>
                <?php
                $arr = $eb->get_skin_dir('popular',EYOOM_THEME_PATH.'/'.$_theme.'/skin_bs');
				$checked['use_gnu_popular1'] = $_eyoom['use_gnu_popular'] == 'n' ? 'checked="checked"':'';
				$checked['use_gnu_popular2'] = $_eyoom['use_gnu_popular'] == 'y' ? 'checked="checked"':'';
				if($arr) {
					echo '<select name="popular_skin" id="popular_skin" required class="required">';
					for ($i=0; $i<count($arr); $i++) {
						if ($i == 0) echo "<option value=\"\">선택</option>";
						echo "<option value=\"".$arr[$i]."\"".get_selected($_eyoom['popular_skin'], $arr[$i]).">".$arr[$i]."</option>\n";
					}
					echo '</select>';
					echo '<label for="use_gnu_popular1"><input type="radio" name="use_gnu_popular" id="use_gnu_popular1" value="n" '.$checked['use_gnu_popular1'].'> 이윰빌더 스킨</label>';
					echo '<label for="use_gnu_popular2"><input type="radio" name="use_gnu_popular" id="use_gnu_popular2" value="y" '.$checked['use_gnu_popular2'].'> 그누보드 스킨</label>';
				} else {
					echo "현재 테마에는 인기검색어 스킨이 존재하지 않습니다.";
				}
				unset($arr);
                ?>
            </td>
            <th scope="row"><label for="theme">설문조사 스킨</label></th>
            <td>
                <?php
                $arr = $eb->get_skin_dir('poll',EYOOM_THEME_PATH.'/'.$_theme.'/skin_bs');
				$checked['use_gnu_poll1'] = $_eyoom['use_gnu_poll'] == 'n' ? 'checked="checked"':'';
				$checked['use_gnu_poll2'] = $_eyoom['use_gnu_poll'] == 'y' ? 'checked="checked"':'';
				if($arr) {
					echo '<select name="poll_skin" id="poll_skin" required class="required">';
					for ($i=0; $i<count($arr); $i++) {
						if ($i == 0) echo "<option value=\"\">선택</option>";
						echo "<option value=\"".$arr[$i]."\"".get_selected($_eyoom['poll_skin'], $arr[$i]).">".$arr[$i]."</option>\n";
					}
					echo '</select>';
					echo '<label for="use_gnu_poll1"><input type="radio" name="use_gnu_poll" id="use_gnu_poll1" value="n" '.$checked['use_gnu_poll1'].'> 이윰빌더 스킨</label>';
					echo '<label for="use_gnu_poll2"><input type="radio" name="use_gnu_poll" id="use_gnu_poll2" value="y" '.$checked['use_gnu_poll2'].'> 그누보드 스킨</label>';
				} else {
					echo "현재 테마에는 설문조사 스킨이 존재하지 않습니다.";
				}
				unset($arr);
                ?>
            </td>
        </tr>
		<tr>
            <th scope="row"><label for="de_admin_company_saupja_no">방문자통계 스킨</label></th>
            <td>
                <?php
                $arr = $eb->get_skin_dir('visit',EYOOM_THEME_PATH.'/'.$_theme.'/skin_bs');
				$checked['use_gnu_visit1'] = $_eyoom['use_gnu_visit'] == 'n' ? 'checked="checked"':'';
				$checked['use_gnu_visit2'] = $_eyoom['use_gnu_visit'] == 'y' ? 'checked="checked"':'';
				if($arr) {
					echo '<select name="visit_skin" id="visit_skin" required class="required">';
					for ($i=0; $i<count($arr); $i++) {
						if ($i == 0) echo "<option value=\"\">선택</option>";
						echo "<option value=\"".$arr[$i]."\"".get_selected($_eyoom['visit_skin'], $arr[$i]).">".$arr[$i]."</option>\n";
					}
					echo '</select>';
					echo '<label for="use_gnu_visit1"><input type="radio" name="use_gnu_visit" id="use_gnu_visit1" value="n" '.$checked['use_gnu_visit1'].'> 이윰빌더 스킨</label>';
					echo '<label for="use_gnu_visit2"><input type="radio" name="use_gnu_visit" id="use_gnu_visit2" value="y" '.$checked['use_gnu_visit2'].'> 그누보드 스킨</label>';
				} else {
					echo "현재 테마에는 방문자통계 스킨이 존재하지 않습니다.";
				}
				unset($arr);
                ?>
            </td>
			<th scope="row"><label for="de_admin_company_saupja_no">새글 스킨</label></th>
            <td>
                <?php
                $arr = $eb->get_skin_dir('new',EYOOM_THEME_PATH.'/'.$_theme.'/skin_bs');
				$checked['use_gnu_new1'] = $_eyoom['use_gnu_new'] == 'n' ? 'checked="checked"':'';
				$checked['use_gnu_new2'] = $_eyoom['use_gnu_new'] == 'y' ? 'checked="checked"':'';
				if($arr) {
					echo '<select name="new_skin" id="new_skin" required class="required">';
					for ($i=0; $i<count($arr); $i++) {
						if ($i == 0) echo "<option value=\"\">선택</option>";
						echo "<option value=\"".$arr[$i]."\"".get_selected($_eyoom['new_skin'], $arr[$i]).">".$arr[$i]."</option>\n";
					}
					echo '</select>';
					echo '<label for="use_gnu_new1"><input type="radio" name="use_gnu_new" id="use_gnu_new1" value="n" '.$checked['use_gnu_new1'].'> 이윰빌더 스킨</label>';
					echo '<label for="use_gnu_new2"><input type="radio" name="use_gnu_new" id="use_gnu_new2" value="y" '.$checked['use_gnu_new2'].'> 그누보드 스킨</label>';
				} else {
					echo "현재 테마에는 새글 스킨이 존재하지 않습니다.";
				}
				unset($arr);
                ?>
            </td>
        </tr>
		<tr>
            <th scope="row"><label for="theme">멤버쉽(회원) 스킨</label></th>
            <td>
                <?php
                $arr = $eb->get_skin_dir('member',EYOOM_THEME_PATH.'/'.$_theme.'/skin_bs');
				$checked['use_gnu_member1'] = $_eyoom['use_gnu_member'] == 'n' ? 'checked="checked"':'';
				$checked['use_gnu_member2'] = $_eyoom['use_gnu_member'] == 'y' ? 'checked="checked"':'';
				if($arr) {
					echo '<select name="member_skin" id="member_skin" required class="required">';
					for ($i=0; $i<count($arr); $i++) {
						if ($i == 0) echo "<option value=\"\">선택</option>";
						echo "<option value=\"".$arr[$i]."\"".get_selected($_eyoom['member_skin'], $arr[$i]).">".$arr[$i]."</option>\n";
					}
					echo '</select>';
					echo '<label for="use_gnu_member1"><input type="radio" name="use_gnu_member" id="use_gnu_member1" value="n" '.$checked['use_gnu_member1'].'> 이윰빌더 스킨</label>';
					echo '<label for="use_gnu_member2"><input type="radio" name="use_gnu_member" id="use_gnu_member2" value="y" '.$checked['use_gnu_member2'].'> 그누보드 스킨</label>';
				} else {
					echo "현재 테마에는 멤버쉽(회원) 스킨이 존재하지 않습니다.";
				}
				unset($arr);
                ?>
            </td>
            <th scope="row"><label for="de_admin_company_saupja_no">FAQ 스킨</label></th>
            <td>
                <?php
                $arr = $eb->get_skin_dir('faq',EYOOM_THEME_PATH.'/'.$_theme.'/skin_bs');
				$checked['use_gnu_faq1'] = $_eyoom['use_gnu_faq'] == 'n' ? 'checked="checked"':'';
				$checked['use_gnu_faq2'] = $_eyoom['use_gnu_faq'] == 'y' ? 'checked="checked"':'';
				if($arr) {
					echo '<select name="faq_skin" id="faq_skin" required class="required">';
					for ($i=0; $i<count($arr); $i++) {
						if ($i == 0) echo "<option value=\"\">선택</option>";
						echo "<option value=\"".$arr[$i]."\"".get_selected($_eyoom['faq_skin'], $arr[$i]).">".$arr[$i]."</option>\n";
					}
					echo '</select>';
					echo '<label for="use_gnu_faq1"><input type="radio" name="use_gnu_faq" id="use_gnu_faq1" value="n" '.$checked['use_gnu_faq1'].'> 이윰빌더 스킨</label>';
					echo '<label for="use_gnu_faq2"><input type="radio" name="use_gnu_faq" id="use_gnu_faq2" value="y" '.$checked['use_gnu_faq2'].'> 그누보드 스킨</label>';
				} else {
					echo "현재 테마에는 FAQ 스킨이 존재하지 않습니다.";
				}
				unset($arr);
                ?>
            </td>
        </tr>
		<tr>
			<th scope="row"><label for="de_admin_company_saupja_no">1:1문의 스킨</label></th>
            <td>
                <?php
                $arr = $eb->get_skin_dir('qa',EYOOM_THEME_PATH.'/'.$_theme.'/skin_bs');
				$checked['use_gnu_qa1'] = $_eyoom['use_gnu_qa'] == 'n' ? 'checked="checked"':'';
				$checked['use_gnu_qa2'] = $_eyoom['use_gnu_qa'] == 'y' ? 'checked="checked"':'';
				if($arr) {
					echo '<select name="qa_skin" id="qa_skin" required class="required">';
					for ($i=0; $i<count($arr); $i++) {
						if ($i == 0) echo "<option value=\"\">선택</option>";
						echo "<option value=\"".$arr[$i]."\"".get_selected($_eyoom['qa_skin'], $arr[$i]).">".$arr[$i]."</option>\n";
					}
					echo '</select>';
					echo '<label for="use_gnu_qa1"><input type="radio" name="use_gnu_qa" id="use_gnu_qa1" value="n" '.$checked['use_gnu_qa1'].'> 이윰빌더 스킨</label>';
					echo '<label for="use_gnu_qa2"><input type="radio" name="use_gnu_qa" id="use_gnu_qa2" value="y" '.$checked['use_gnu_qa2'].'> 그누보드 스킨</label>';
				} else {
					echo "현재 테마에는 1:1문의 스킨이 존재하지 않습니다.";
				}
				unset($arr);
                ?>
            </td>
            <th scope="row"><label for="theme">검색 스킨</label></th>
            <td>
                <?php
                $arr = $eb->get_skin_dir('search',EYOOM_THEME_PATH.'/'.$_theme.'/skin_bs');
				$checked['use_gnu_search1'] = $_eyoom['use_gnu_search'] == 'n' ? 'checked="checked"':'';
				$checked['use_gnu_search2'] = $_eyoom['use_gnu_search'] == 'y' ? 'checked="checked"':'';
				if($arr) {
					echo '<select name="search_skin" id="search_skin" required class="required">';
					for ($i=0; $i<count($arr); $i++) {
						if ($i == 0) echo "<option value=\"\">선택</option>";
						echo "<option value=\"".$arr[$i]."\"".get_selected($_eyoom['search_skin'], $arr[$i]).">".$arr[$i]."</option>\n";
					}
					echo '</select>';
					echo '<label for="use_gnu_search1"><input type="radio" name="use_gnu_search" id="use_gnu_search1" value="n" '.$checked['use_gnu_search1'].'> 이윰빌더 스킨</label>';
					echo '<label for="use_gnu_search2"><input type="radio" name="use_gnu_search" id="use_gnu_search2" value="y" '.$checked['use_gnu_search2'].'> 그누보드 스킨</label>';
				} else {
					echo "현재 테마에는 검색 스킨이 존재하지 않습니다.";
				}
				unset($arr);
                ?>
            </td>
        </tr>
		<tr>
			<th scope="row"><label for="de_admin_company_saupja_no">쇼핑몰 스킨</label></th>
            <td>
                <?php
                $arr = $eb->get_skin_dir('shop',EYOOM_THEME_PATH.'/'.$_theme.'/skin_bs');
				$checked['use_gnu_shop1'] = $_eyoom['use_gnu_shop'] == 'n' ? 'checked="checked"':'';
				$checked['use_gnu_shop2'] = $_eyoom['use_gnu_shop'] == 'y' ? 'checked="checked"':'';
				if($arr) {
					echo '<select name="shop_skin" id="shop_skin" required class="required">';
					for ($i=0; $i<count($arr); $i++) {
						if ($i == 0) echo "<option value=\"\">선택</option>";
						echo "<option value=\"".$arr[$i]."\"".get_selected($_eyoom['shop_skin'], $arr[$i]).">".$arr[$i]."</option>\n";
					}
					echo '</select>';
					echo '<label for="use_gnu_shop1"><input type="radio" name="use_gnu_shop" id="use_gnu_shop1" value="n" '.$checked['use_gnu_shop1'].'> 이윰빌더 스킨</label>';
					echo '<label for="use_gnu_shop2"><input type="radio" name="use_gnu_shop" id="use_gnu_shop2" value="y" '.$checked['use_gnu_shop2'].'> 영카트 스킨</label>';
				} else {
					echo "현재 테마에는 쇼핑몰 스킨이 존재하지 않습니다.";
				}
				unset($arr);
                ?>

            </td>
			<th scope="row"><label for="de_admin_company_saupja_no">팝업 스킨</label></th>
            <td>
                <?php
                $arr = $eb->get_skin_dir('newwin',EYOOM_THEME_PATH.'/'.$_theme.'/skin_bs');
				$checked['use_gnu_newwin1'] = $_eyoom['use_gnu_newwin'] == 'n' ? 'checked="checked"':'';
				$checked['use_gnu_newwin2'] = $_eyoom['use_gnu_newwin'] == 'y' ? 'checked="checked"':'';
				if($arr) {
					echo '<select name="newwin_skin" id="newwin_skin" required class="required">';
					for ($i=0; $i<count($arr); $i++) {
						if ($i == 0) echo "<option value=\"\">선택</option>";
						echo "<option value=\"".$arr[$i]."\"".get_selected($_eyoom['newwin_skin'], $arr[$i]).">".$arr[$i]."</option>\n";
					}
					echo '</select>';
					echo '<label for="use_gnu_newwin1"><input type="radio" name="use_gnu_newwin" id="use_gnu_newwin1" value="n" '.$checked['use_gnu_newwin1'].'> 이윰빌더 스킨</label>';
					echo '<label for="use_gnu_newwin2"><input type="radio" name="use_gnu_newwin" id="use_gnu_newwin2" value="y" '.$checked['use_gnu_newwin2'].'> 그누보드 스킨</label>';
				} else {
					echo "현재 테마에는 팝업 스킨이 존재하지 않습니다.";
				}
				unset($arr);
                ?>
            </td>
        </tr>
		<tr>
            <th scope="row"><label for="theme">마이페이지 스킨</label></th>
            <td>
                <?php
                $arr = $eb->get_skin_dir('mypage',EYOOM_THEME_PATH.'/'.$_theme.'/skin_bs');
				if($arr) {
					echo '<select name="mypage_skin" id="mypage_skin" required class="required">';
					for ($i=0; $i<count($arr); $i++) {
						if ($i == 0) echo "<option value=\"\">선택</option>";
						echo "<option value=\"".$arr[$i]."\"".get_selected($_eyoom['mypage_skin'], $arr[$i]).">".$arr[$i]."</option>\n";
					}
					echo '</select>';
				} else {
					echo "현재 테마에는 마이페이지 스킨이 존재하지 않습니다.";
				}
				unset($arr);
                ?>
            </td>
            <th scope="row"><label for="de_admin_company_saupja_no">서명 스킨</label></th>
            <td>
                <?php
                $arr = $eb->get_skin_dir('signature',EYOOM_THEME_PATH.'/'.$_theme.'/skin_bs');
				if($arr) {
					echo '<select name="signature_skin" id="signature_skin" required class="required">';
					for ($i=0; $i<count($arr); $i++) {
						if ($i == 0) echo "<option value=\"\">선택</option>";
						echo "<option value=\"".$arr[$i]."\"".get_selected($_eyoom['signature_skin'], $arr[$i]).">".$arr[$i]."</option>\n";
					}
					echo '</select>';
				} else {
					echo "현재 테마에는 서명 스킨이 존재하지 않습니다.";
				}
				unset($arr);
                ?>
            </td>
        </tr>
		<tr>
            <th scope="row"><label for="de_admin_company_saupja_no">내글반응 스킨</label></th>
            <td>
                <?php
                $arr = $eb->get_skin_dir('respond',EYOOM_THEME_PATH.'/'.$_theme.'/skin_bs');
				if($arr) {
					echo '<select name="respond_skin" id="respond_skin" required class="required">';
					for ($i=0; $i<count($arr); $i++) {
						if ($i == 0) echo "<option value=\"\">선택</option>";
						echo "<option value=\"".$arr[$i]."\"".get_selected($_eyoom['respond_skin'], $arr[$i]).">".$arr[$i]."</option>\n";
					}
					echo '</select>';
				} else {
					echo "현재 테마에는 내글반응 스킨이 존재하지 않습니다.";
				}
				unset($arr);
                ?>
            </td>
			<th scope="row"><label for="de_admin_company_saupja_no">푸시알림 스킨</label></th>
            <td>
                <?php
                $arr = $eb->get_skin_dir('push',EYOOM_THEME_PATH.'/'.$_theme.'/skin_bs');
				if($arr) {
					echo '<select name="push_skin" id="push_skin" required class="required">';
					for ($i=0; $i<count($arr); $i++) {
						if ($i == 0) echo "<option value=\"\">선택</option>";
						echo "<option value=\"".$arr[$i]."\"".get_selected($_eyoom['push_skin'], $arr[$i]).">".$arr[$i]."</option>\n";
					}
					echo '</select>';
				} else {
					echo "현재 테마에는 푸시알림 스킨이 존재하지 않습니다.";
				}
				unset($arr);
                ?>
            </td>
		</tr>
        </tbody>
        </table>
    </div>
</section>

<?php echo $frm_submit; ?>


<section id="anc_scf_info">
    <h2 class="h2_frm">[<b style='color:#f30;'><?php echo $_theme;?></b> 테마] 기타설정 </h2>

    <div class="tbl_frm01 tbl_wrap">
        <table>
        <caption>스킨설정</caption>
        <colgroup>
            <col class="grid_4">
            <col>
        </colgroup>
        <tbody>
		<tr>
            <th scope="row"><label for="use_eyoom_menu">이윰메뉴</label></th>
            <td>
                <label for="use_eyoom_menu1"><input type="radio" name="use_eyoom_menu" id="use_eyoom_menu1" value="y" <?php if($_eyoom['use_eyoom_menu'] == 'y') echo "checked";?>> 이윰메뉴 사용</label>
				<label for="use_eyoom_menu2"><input type="radio" name="use_eyoom_menu" id="use_eyoom_menu2" value="n" <?php if($_eyoom['use_eyoom_menu'] == 'n') echo "checked";?>> 그누메뉴 사용</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="theme">그누레벨 아이콘 </label></th>
            <td>
                <?php
				if($_eyoom['use_level_icon_gnu'] == 'y') $checked['use_level_icon_gnu'] = 'checked="checked"';
                $arr = $eb->get_skin_dir('gnuboard',EYOOM_THEME_PATH.'/'.$_theme.'/image/level_icon');
				if($arr) {
					echo '<select name="level_icon_gnu" id="level_icon_gnu">';
					for ($i=0; $i<count($arr); $i++) {
						if ($i == 0) echo "<option value=\"\">선택</option>";
						echo "<option value=\"".$arr[$i]."\"".get_selected($_eyoom['level_icon_gnu'], $arr[$i]).">".$arr[$i]."</option>\n";
					}
					echo '</select>';
					echo '<label for="use_level_icon_gnu"><input type="checkbox" name="use_level_icon_gnu" value="y" id="use_level_icon_gnu" '.$checked['use_level_icon_gnu'].'> 사용</label>';
				} else {
					echo "현재 테마에는 그누레벨 아이콘이 존재하지 않습니다.";
				}
				unset($arr);
                ?>
			</td>
		</tr>
		<tr>
            <th scope="row"><label for="theme">이윰레벨 아이콘 </label></th>
            <td>
                <?php
				if($_eyoom['use_level_icon_eyoom'] == 'y') $checked['use_level_icon_eyoom'] = 'checked="checked"';
                $arr = $eb->get_skin_dir('eyoom',EYOOM_THEME_PATH.'/'.$_theme.'/image/level_icon');
				if($arr) {
					echo '<select name="level_icon_eyoom" id="level_icon_eyoom">';
					for ($i=0; $i<count($arr); $i++) {
						if ($i == 0) echo "<option value=\"\">선택</option>";
						echo "<option value=\"".$arr[$i]."\"".get_selected($_eyoom['level_icon_eyoom'], $arr[$i]).">".$arr[$i]."</option>\n";
					}
					echo '</select>';
					echo '<label for="use_level_icon_eyoom"><input type="checkbox" name="use_level_icon_eyoom" value="y" id="use_level_icon_gnu" '.$checked['use_level_icon_eyoom'].'> 사용</label>';
				} else {
					echo "현재 테마에는 이윰레벨 아이콘이 존재하지 않습니다.";
				}
				unset($arr);
                ?>
			</td>
		</tr>
		<tr>
            <th scope="row"><label for="bo_use_sideview">회원 사이드뷰</label></th>
            <td>
                <label for="use_sideview1"><input type="radio" name="use_sideview" id="use_sideview1" value="y" <?php if($_eyoom['use_sideview'] == 'y') echo "checked";?>> 사용</label>
				<label for="use_sideview2"><input type="radio" name="use_sideview" id="use_sideview2" value="n" <?php if($_eyoom['use_sideview'] == 'n') echo "checked";?>> 사용하지 않음</label>
            </td>
        </tr>
		<tr>
            <th scope="row"><label for="theme">푸시알람 </label></th>
            <td>
				<label for="push_reaction1"><input type="radio" name="push_reaction" id="push_reaction1" value="y" <?php if($_eyoom['push_reaction'] == 'y') echo "checked";?>> 사용</label>
				<label for="push_reaction2"><input type="radio" name="push_reaction" id="push_reaction2" value="n" <?php if($_eyoom['push_reaction'] == 'n') echo "checked";?>> 사용하지 않음</label>
			</td>
		</tr>
		<tr>
            <th scope="row"><label for="theme">푸시체크 반복시간 </label></th>
            <td>
				<input type="text" name="push_time" value="<?php echo $_eyoom['push_time'];?>" id="push_time" style="width:80px;" class="frm_input"> <span class="exp">1000 -> 1초</span>
			</td>
		</tr>
		<tr>
            <th scope="row"><label for="theme">프로필 사진 사이즈 </label></th>
            <td>
				가로 : <input type="text" name="photo_width" value="<?php echo $_eyoom['photo_width'];?>" id="photo_width" class="frm_input" style="width:80px;">px, 세로 : <input type="text" name="photo_height" value="<?php echo $_eyoom['photo_height'];?>" id="photo_height" class="frm_input" style="width:80px;">px  <span class="exp">자동으로 이미지 사이즈를 지정한 사이즈로 썸네일화 합니다.</span>
			</td>
		</tr>
		<tr>
            <th scope="row"><label for="theme">마이홈 커버사진 가로사이즈 </label></th>
            <td>
				<input type="text" name="cover_width" value="<?php echo $_eyoom['cover_width'];?>" id="cover_width" class="frm_input" style="width:80px;">px
			</td>
		</tr>
		</table>
		<input type="hidden" name="push_sound" id="push_sound" value="push_sound_01.mp3">
	</div>
</section>

<?php echo $frm_submit; ?>
<?php } ?>

<?php
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>
