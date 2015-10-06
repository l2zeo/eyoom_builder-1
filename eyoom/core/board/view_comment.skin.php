<?php
 
	if (!defined('_GNUBOARD_')) exit;

	unset($comment);
	$cmt_amt = count($list);
	for ($i=0; $i<$cmt_amt; $i++) {
		$comment[$i]['comment_id'] = $list[$i]['wr_id'];
		$comment[$i]['cmt_depth'] = "";
		$comment[$i]['cmt_depth'] = strlen($list[$i]['wr_comment_reply']) * 20;
		$content = $list[$i]['content'];
		$comment[$i]['comment'] = preg_replace("/\[\<a\s.*href\=\"(http|https|ftp|mms)\:\/\/([^[:space:]]+)\.(mp3|wma|wmv|asf|asx|mpg|mpeg)\".*\<\/a\>\]/i", "<script>doc_write(obj_movie('$1://$2.$3'));</script>", $content);
		$comment[$i]['comment'] = $eb->eyoom_content($comment[$i]['comment']);
		$comment[$i]['cmt_sv'] = $cmt_amt - $i + 1; // 댓글 헤더 z-index 재설정 ie8 이하 사이드뷰 겹침 문제 해결
		$comment[$i]['wr_name'] = get_text($list[$i]['wr_name']);
		$comment[$i]['wr_email'] = $list[$i]['wr_email'];
		$comment[$i]['wr_homepage'] = $list[$i]['wr_homepage'];
		$comment[$i]['name'] = $list[$i]['name'];
		$comment[$i]['mb_id'] = $list[$i]['mb_id'];
		$comment[$i]['ip'] = $list[$i]['ip'];
		$comment[$i]['datetime'] = $list[$i]['datetime'];
		$comment[$i]['wr_option'] = $list[$i]['wr_option'];
		$comment[$i]['content1'] = get_text($list[$i]['content1'], 0);

		$level = $list[$i]['wr_1'] ? $eb->level_info($list[$i]['wr_1']):'';
		if(!$level['anonymous']) {
			$comment[$i]['mb_photo'] = $eb->mb_photo($list[$i]['mb_id']);
			$comment[$i]['gnu_level'] = $level['gnu_level'];
			$comment[$i]['eyoom_level'] = $level['eyoom_level'];
			$comment[$i]['lv_gnu_name'] = $level['gnu_name'];
			$comment[$i]['lv_name'] = $level['name'];
			$comment[$i]['gnu_icon'] = $level['gnu_icon'];
			$comment[$i]['eyoom_icon'] = $level['eyoom_icon'];
		} else {
			list($gnu_level,$eyoom_level,$anonymous) = explode('|',$list[$i]['wr_1']);
			$comment[$i]['anonymous_id'] = $anonymous ? $gnu_level."|".$eyoom_level:'';
			$comment[$i]['mb_id'] = 'anonymous';
			$comment[$i]['wr_name'] = '익명글';
			$comment[$i]['email'] = '';
			$comment[$i]['homepage'] = '';
			$comment[$i]['gnu_level'] = '';
			$comment[$i]['eyoom_level'] = '';
			$comment[$i]['lv_gnu_name'] = '';
			$comment[$i]['lv_name'] = '';
			$comment[$i]['gnu_icon'] = '';
			$comment[$i]['eyoom_icon'] = '';
		}

		if($list[$i]['is_reply'] || $list[$i]['is_edit'] || $list[$i]['is_del']) {
			$comment[$i]['is_reply'] = $list[$i]['is_reply'];
			$comment[$i]['is_edit'] = $list[$i]['is_edit'];
			$comment[$i]['is_del'] = $list[$i]['is_del'];
			$comment[$i]['del_link'] = $list[$i]['del_link'];
			$query_string = str_replace("&", "&amp;", $_SERVER['QUERY_STRING']);

			if($w == 'cu') {
				$sql = " select wr_id, wr_content from $write_table where wr_id = '$c_id' and wr_is_comment = '1' ";
				$cmt = sql_fetch($sql);
				$comment[$i]['c_wr_content'] = $cmt['wr_content'];
			}
			

			$comment[$i]['c_reply_href'] = './board.php?'.$query_string.'&amp;c_id='.$comment[$i]['comment_id'].'&amp;w=c#bo_vc_w';
			$comment[$i]['c_edit_href'] = './board.php?'.$query_string.'&amp;c_id='.$comment[$i]['comment_id'].'&amp;w=cu#bo_vc_w';
		}
		
	}

	if($board['bo_use_sns']) {
		ob_start();
		include_once (G5_SNS_PATH."/view_comment_list.sns.skin.php");
		$comment_sns = ob_get_contents();
		ob_end_clean();
	}

	// 사용자 프로그램
	@include_once(EYOOM_USER_PATH.'/board/view_comment.skin.php');

	// Template assign
	@include EYOOM_INC_PATH.'/tpl.assign.php';
?>