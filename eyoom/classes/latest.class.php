<?php
class latest extends eyoom
{
	public function __construct() {
	}

	// 새글 새댓글 최신글 동시에 추출하기
	public function latest_newpost($skin, $option) {
		$list['write'] = $this->latest_write($skin, $option, false);
		$list['comment'] = $this->latest_comment($skin, $option, false);
		$this->latest_print($skin, $list, 'multiple', 'newpost');
	}

	// 히트수가 높은 순으로 게시물 추출
	public function latest_hot($skin, $option) {
		global $bo_table;
		$where = 1;
		$opt = $this->get_option($option);
		$where .= $opt['where'];
		$where .= " and wr_id = wr_parent";
		$where .= $bo_table ? " and bo_table = '{$bo_table}'":'';
		$orderby = " wr_hit desc ";
		$list = $this->latest_assign($where, $opt['count'], $opt['cut_subject'], $opt['cut_content'], $orderby);
		$this->latest_print($skin, $list, '', 'hotpost');
	}

	// 이윰 최신글 추출
	public function latest_eyoom($skin, $option, $print=true) {
		$where = 1;
		$opt = $this->get_option($option);
		$where .= $opt['where'];
		$where .= " and wr_id = wr_parent";
		$orderby = $opt['best']=='y'? " wr_hit desc ":"";
		$list = $this->latest_assign($where, $opt['count'], $opt['cut_subject'], $opt['cut_content'], $orderby, $opt['bo_direct']);
		if($print === null) $print = true;
		if($print) {
			$this->latest_print($skin, $list,'single','latest');
		} else {
			return $list;
		}
	}

	// 최신글 추출
	public function latest_write($skin, $option, $print=true) {
		$where = 1;
		$opt = $this->get_option($option);
		$where .= $opt['where'];
		$where .= " and wr_id = wr_parent";
		$list = $this->latest_assign($where, $opt['count'], $opt['cut_subject'], $opt['cut_content']);
		if($print === null) $print = true;
		if($print) {
			$this->latest_print($skin, $list);
		} else {
			return $list;
		}
	}

	// 최신 댓글 추출
	public function latest_comment($skin, $option, $print=true) {
		$where = 1;
		$opt = $this->get_option($option);
		$where .= $opt['where'];
		$where .= " and wr_id <> wr_parent";
		$list = $this->latest_assign($where, $opt['count'], $opt['cut_subject'], $opt['cut_content']);
		if($print === null) $print = true;
		if($print) {
			$this->latest_print($skin, $list);
		} else {
			return $list;
		}
	}

	// 옵션 값 분석
	protected function option_query($str) {
		if($str) {
			$tmp = explode("||", $str);
			if(is_array($tmp)) {
				foreach($tmp as $set) {
					list($key,$val) = explode("=",$set);
					$outvar[trim($key)] = trim($val);
				}
				return $outvar;
			}
		} else return false;
	}

	// 옵션셋으로 의미있는 정보로 변경하여 가져옴
	protected function get_option($option) {
		global $g5;
		if($option) {
			$optset = $this->option_query($option);
			$where  = $optset['where'] ? $this->latest_where($optset['where']):'';

			// 특정게시판만 가져오기
			if($optset['bo_table']) {
				$where .= $optset['bo_direct'] != 'y' ? " and bo_table = '{$optset['bo_table']}'":"";
				$this->bo_table = $optset['bo_table'];
			} else {
				// 제외 게시판
				if($optset['bo_exclude']) {
					$bo_exclude = explode(",", $optset['bo_exclude']);
					foreach($bo_exclude as $k => $v) {
						if(!$v) continue;
						$exclude[$k] = trim($v);
					}
					if($exclude) $where .= " and find_in_set(bo_table,'".implode(',',$exclude)."') = 0 ";
				}

				// 포함 게시판
				if($optset['bo_include']) {
					$bo_exclude = explode(",", $optset['bo_include']);
					foreach($bo_exclude as $k => $v) {
						if(!$v) continue;
						$include[$k] = trim($v);
					}
					if($include) $where .= " and find_in_set(bo_table,'".implode(',',$include)."') ";
				}

				// 그룹아이디(gr_id) 가 있을 경우
				if($optset['gr_id']) {
					$res = sql_query("select bo_table from {$g5['board_table']} where gr_id='{$optset['gr_id']}' order by bo_table ");
					for($i=0; $row=sql_fetch_array($res); $i++) {
						// bo_exclude 에 포함되어 있는 게시판이 있다면 제외
						if($exclude && @in_array($row['bo_table'], $exclude)) continue;
						$gr_board[$i] = $row['bo_table'];
					}
					if($gr_board) $where .= " and find_in_set(bo_table,'".implode(',',$gr_board)."') ";
				}
			}

			// 기간설정 period=20 오늘부터 20일전 데이타
			if($optset['period']) {
				$start = date("YmdHis", strtotime("-".$optset['period']." day"));
				$end = date("YmdHis");
				$where .= " and bn_datetime between date_format(".$start.", '%Y-%m-%d 00:00:00') and date_format(".$end.", '%Y-%m-%d 23:59:59')";
			}

			// 조건검색
			$opt['where'] = $where;

			// 최신글 헤더 타이틀 
			if($optset['title']) {
				if($optset['bo_table']) {
					$this->header_title = "<a href='".G5_BBS_URL."/board.php?bo_table=".$optset['bo_table']."'>".$optset['title']."</a>";
				} else if($optset['gr_id']) {
					$this->header_title = "<a href='".G5_BBS_URL."/group.php?gr_id=".$optset['gr_id']."'>".$optset['title']."</a>";
				} else {
					$this->header_title = $optset['title'];
				}
			}

			// 출력갯수
			if($optset['count']) $opt['count'] = $optset['count'];

			// 최신글 제목길이
			if($optset['cut_subject']) $opt['cut_subject'] = $optset['cut_subject'];

			// 최신글 출력내용 길이
			if($optset['cut_content']) $opt['cut_content'] = $optset['cut_content'];

			// 게시판에서 직접 가져오기
			if($optset['bo_direct']) $opt['bo_direct'] = $optset['bo_direct'];

			// 베스트글 여부
			if($optset['best']) $opt['best'] = $optset['best'];

			// 타입 [회원랭킹]
			if($optset['type']) $opt['type'] = $optset['type'];

			// 사용자 사진여부
			if($optset['photo']) $this->photo = $optset['photo']; else $this->photo = 'n';

			// 컨텐츠 출력여부
			if($optset['content']) $this->content = $optset['content']; else $this->content = 'n';

			// 이미지 출력여부
			if($optset['img_view']) $this->img_view = $optset['img_view']; else $this->img_view = 'n';

			// 이미지 가로 이미지 수
			if($optset['cols']) $this->cols = $optset['cols']; else $this->cols = '3';

			// 이미지 가로사이즈
			if($optset['img_width']) $this->img_width = $optset['img_width']; else $this->img_width = '500';

			// 이미지 세로사이즈
			if($optset['img_height']) $this->img_height = $optset['img_height']; else $this->img_height = '0';

			return $opt;

		} else return false;
	}

	private function latest_where($expression) {
		$where = $expression;
		$where = preg_replace("/\s+/i","",$where);
		$where = preg_replace("/:/i","=",$where);
		$where = preg_replace("/&/i"," and ",$where);
		$where = preg_replace("/\|/i"," or ",$where);
		$where = preg_replace("/\"/i","'",$where);
		$where = " and " . $where;
		return $where;
	}

	// 최신글 정보 DB에서 가져오기
	protected function latest_assign($where, $cnt, $cut_subject=20, $cut_content=100, $orderby='', $direct='n') {
		global $g5, $eb, $is_admin, $member;

		if($direct == 'n' || $direct == '') {
			if(!$orderby) $orderby = " bn_datetime desc ";
			$sql = "select * from {$g5['eyoom_new']} where $where order by $orderby limit $cnt";
		} else if($direct == 'y') {
			if(!$orderby) $orderby = " wr_datetime desc ";
			$sql = "select * from ".$g5['write_prefix'].$this->bo_table." where $where order by $orderby limit $cnt";
		}

		$result = sql_query($sql, false);
		for($i=0; $row = sql_fetch_array($result); $i++) {
			$list[$i] = $row;
			$bo_table = $direct!='y' ? $row['bo_table']:$this->bo_table;
			$list[$i]['mb_photo'] = $eb->mb_photo($row['mb_id']);
			if(!$row['wr_subject']) {
				if(preg_match('/secret/',$row['wr_option']) && !$is_admin && $member['mb_id']!=$row['mb_id']) {
					$list[$i]['wr_subject'] = '비밀 댓글입니다.';
					$list[$i]['wr_content'] = '비밀 댓글입니다.';
				} else {
					$list[$i]['wr_subject'] = conv_subject($row['wr_content'], $cut_subject, '…');
				}
				$list[$i]['href'] = G5_BBS_URL."/board.php?bo_table={$bo_table}&amp;wr_id={$row['wr_id']}#c_{$row['wr_id']}";
			} else {
				if(preg_match('/secret/',$row['wr_option']) && !$is_admin && $member['mb_id']!=$row['mb_id']) {
					$list[$i]['wr_subject'] = '비밀글입니다.';
					$list[$i]['wr_content'] = '비밀글입니다.';
				} else {
					$list[$i]['wr_subject'] = conv_subject($row['wr_subject'], $cut_subject, '…');
					if($this->content == 'y') $list[$i]['wr_content'] = cut_str(strip_tags($row['wr_content']), $cut_content, '…');
				}
				// 옵션으로 이미지 가져오기
				if($this->img_view == 'y') {
					$list[$i]['image'] = $this->latest_image($row,$direct);
				}
				$list[$i]['href'] = G5_BBS_URL."/board.php?bo_table={$bo_table}&amp;wr_id={$row['wr_parent']}";
			}

			$list[$i]['wr_hit'] = $row['wr_hit'];
			$list[$i]['datetime'] = $row['bn_datetime'];
		}
		return $list;
	}

	protected function latest_image($source,$direct='n') {
		global $g5;
		switch($direct) {
			case 'y':
				$thumb = get_list_thumbnail($this->bo_table, $source['wr_id'], $this->img_width, $this->img_height);
				$image = $thumb['src'];
				break;
			default :
				$images = unserialize($source['wr_image']);
				if(is_array($images)) {
					for($k=0;$k<count($images['bf']);$k++) {
						if(!$images['bf'][$k]) continue;
						else $img = $images['bf'][$k];
					}
					if(!$img) {
						for($j=0;$j<count($images['url']);$j++) {
							if(!$images['url'][$j]) continue;
							else $img = $images['url'][$j];
						}
					}
					$imgfile = G5_PATH.$img;
					if(file_exists($imgfile)) {
						$img_path = explode('/',$img);
						for($i=0;$i<count($img_path)-1;$i++) {
							$path[$i] = $img_path[$i]; 
						}
						$filename = $img_path[count($img_path)-1];
						$filepath = G5_PATH.implode('/',$path);
						$tname = thumbnail($filename, $filepath, $filepath, $this->img_width, $this->img_height,'');
						$image = G5_URL.implode('/',$path).'/'.$tname;
					}
				}
				break;
		}
		if($image) return $image;
	}

	// 스킨파일 위치에 출력하기
	protected function latest_print($skin, $arr, $mode='single', $folder='latest') {
		global $tpl, $tpl_name, $board;

		if(!$mode) $mode='single';
		if(!$folder) $folder='latest';

		$tpl->define_template($folder,$skin,'latest.skin.html');
		if($this->header_title) $tpl->assign(array('title' => $this->header_title));
		$tpl->assign(array(
			'bo_table' => $this->bo_table,
			'photo' => $this->photo,
			'content' => $this->content,
			'cols' => $this->cols,
		));
		if($mode=='single') {
			$tpl->assign(array(
				'loop' => $arr,
			));
		} else if($mode='multiple') {
			$tpl->assign($arr);
		}
		$tpl->print_($tpl_name);
	}

	// 회원 랭킹 
	public function latest_ranking($skin, $option, $type='') {
		global $g5, $config, $tpl, $tpl_name, $eb;
		$where = 1;
		if(!$type) $opt = $this->get_option($option);
		else {
			$opt['count'] = $option;
			$opt['type'] = $type;
		}

		switch($opt['type']) {
			// 오늘의 포인트 랭킹
			case "today_point":
				$start = date("Ymd").'000000';
				$end = date("Ymd").'595959';
				
				$sql = "select mb_id, sum(po_point) as po_point from {$g5['point_table']} where po_point > 0 and mb_id <> '{$config['cf_admin']}' and (date_format(po_datetime, '%Y%m%d%H%i%s') between '{$start}' and '{$end}') group by mb_id order by sum(po_point) desc limit {$opt['count']}";
				$res = sql_query($sql, false);

				for($i=0; $row=sql_fetch_array($res); $i++) {
					$mbinfo = sql_fetch("select a.level, b.* from {$g5['eyoom_member']} as a left join {$g5['member_table']} as b on a.mb_id=b.mb_id where b.mb_id='{$row['mb_id']}'",false);
					$mbinfo['point'] = $row['po_point'];
					$list[$i] = $mbinfo;
					$level_info = $mbinfo['mb_level'].'|'.$mbinfo['level'];
					$level = $eb->level_info($level_info);
					$list[$i]['eyoom_icon'] = $level['eyoom_icon'];
					$list[$i]['grade_icon'] = $level['grade_icon'];
				}
				break;

			// 전체 포인트 랭키
			case "total_point":
				$result = sql_query("select a.level, b.* from {$g5['eyoom_member']} as a left join {$g5['member_table']} as b on a.mb_id=b.mb_id where b.mb_email_certify!='0000-00-00 00:00:00' and b.mb_level!='10' order by b.mb_point desc limit {$opt['count']}", false);
				for ($i=0; $row=sql_fetch_array($result); $i++) {
					$list[$i] = $row;
					$list[$i]['point'] = $row['mb_point'];
					$level_info = $row['mb_level'].'|'.$row['level'];
					$level = $eb->level_info($level_info);
					$list[$i]['eyoom_icon'] = $level['eyoom_icon'];
					$list[$i]['grade_icon'] = $level['grade_icon'];
				}
				break;

			// 레벨 랭킹
			case "level_point":
				$result = sql_query("select a.level,a.level_point,b.* from {$g5['eyoom_member']} as a left join {$g5['member_table']} as b on a.mb_id=b.mb_id where b.mb_email_certify!='0000-00-00 00:00:00' and b.mb_level!='10' order by a.level_point desc limit {$opt['count']}", false);
				for ($i=0; $row=sql_fetch_array($result); $i++) {
					$list[$i] = $row;
					$list[$i]['point'] = $row['level_point'];
					$level_info = $row['mb_level'].'|'.$row['level'];
					$level = $eb->level_info($level_info);
					$list[$i]['eyoom_icon'] = $level['eyoom_icon'];
					$list[$i]['grade_icon'] = $level['grade_icon'];
				}
				break;
		}
		if(!$type){
			$tpl->define_template("ranking",$skin,'ranking.skin.html');
			$tpl->assign('list',$list);
			$tpl->print_($tpl_name);
		} else {
			return $list;
		}
	}

	// 랭킹 SET
	public function latest_rankset($skin,$count) {
		global $tpl, $tpl_name;

		$list['rank_today'] = $this->latest_ranking($skin, $count, 'today_point');
		$list['rank_total'] = $this->latest_ranking($skin, $count, 'total_point');
		$list['rank_level'] = $this->latest_ranking($skin, $count, 'level_point');
		$tpl->define_template("ranking",$skin,'rankset.skin.html');
		$tpl->assign($list);
		$tpl->print_($tpl_name);
	}

	// 베스트 SET
	public function latest_bestset($skin,$option,$bo_table='') {
		global $tpl, $tpl_name;

		$opt = '';
		$optset = $this->option_query($option);
		$title = $optset['title'] ? $optset['title']:'';
		$_option['today'] = "best=y||period=1";
		$_option['week'] = "best=y||period=7";
		$_option['month'] = "best=y||period=30";

		$opt .= $optset['count'] ? "||count=".$optset['count']:"||count=10";
		$opt .= $optset['cut_subject'] ? "||cut_subject=".$optset['cut_subject']:"||cut_subject=30";
		if($bo_table) {
			$opt .= "||bo_table=".$bo_table;
		} else {
			$opt .= $optset['bo_include'] ? "||bo_include=".$optset['bo_include']:"";
			$opt .= $optset['bo_exclude'] ? "||bo_exclude=".$optset['bo_exclude']:"";
			$opt .= $optset['gr_id'] ? "||gr_id=".$optset['gr_id']:"";
		}
		$opt .= $optset['where'] ? "||where=".$optset['where']:"";

		$_option['today'] .= $opt;
		$_option['week'] .= $opt;
		$_option['month'] .= $opt;

		$list['today'] = $this->latest_eyoom($skin, $_option['today'], false);
		$list['week'] = $this->latest_eyoom($skin, $_option['week'], false);
		$list['month'] = $this->latest_eyoom($skin, $_option['month'], false);
		$tpl->define_template("best",$skin,'bestset.skin.html');
		$tpl->assign($list);
		$tpl->assign('title',$title);
		$tpl->print_($tpl_name);
	}
	
	// 쇼핑몰 상품 추출하기
	public function latest_item($skin, $option) {
		global $g5, $config, $tpl, $tpl_name, $eb;
		$where = 1;
		$opt = $this->get_item_option($option);

		$where .= $opt['where'];
		$where .= " and it_soldout = '0'";
		$where .= $opt['type'] ? " and it_type".$opt['type']." = '1'":'';
		if($opt['ca_id']) {
			$length = strlen($opt['ca_id']);
			switch($length) {
				case 2: $where .= " and ca_id = '{$opt['ca_id']}' "; break;
				case 4: $where .= " and ca_id2 = '{$opt['ca_id']}' "; break;
				case 6: $where .= " and ca_id3 = '{$opt['ca_id']}' "; break;
			}
		}
		
		$orderby = " it_time desc ";
		$list = $this->latest_item_assign($where, $opt['count'], $opt['cut_name'], $orderby, $opt['width']);
		$this->latest_item_print($skin, $list,'latest');
	}

	// 상품 추출 옵션
	private function get_item_option($option) {
		global $g5;
		if($option) {
			$optset = $this->option_query($option);
			$where  = $optset['where'] ? $this->latest_where($optset['where']):'';

			// 기간설정 period=20 오늘부터 20일전 데이타
			if($optset['period']) {
				$start = date("YmdHis", strtotime("-".$optset['period']." day"));
				$end = date("YmdHis");
				$where .= " and it_update_time between date_format(".$start.", '%Y-%m-%d 00:00:00') and date_format(".$end.", '%Y-%m-%d 23:59:59')";
			}

			// 조건검색
			$opt['where'] = $where;
		
			// 최신글 헤더 타이틀 
			if($optset['title']) {
				$this->header_title = $optset['title'];
			}
		
			if($optset['it_id']) {
				$opt['it_id'] = $optset['it_id'];
			}
		
			// 출력갯수
			if($optset['count']) $opt['count'] = $optset['count'];
		
			// 최신글 제목길이
			if($optset['cut_name']) $opt['cut_name'] = $optset['cut_name'];
			
			// 상품이미지 가로
			if($optset['width']) $opt['width'] = $optset['width'];
		
			// 타입
			if($optset['type']) $opt['type'] = $optset['type'];

			return $opt;

		} else return false;
	}

	private function latest_item_assign($where, $cnt=5, $cut_name=100, $orderby='', $width=120) {
		global $g5, $eb;

		if(!$orderby) $orderby = " it_time desc ";
		$sql = "select * from {$g5['g5_shop_item_table']} where $where order by $orderby limit $cnt";

		$result = sql_query($sql, false);
		for($i=0; $row = sql_fetch_array($result); $i++) {
			$list[$i] = $row;

			$list[$i]['it_name'] = conv_subject($row['it_name'], $cut_name, '…');
			$list[$i]['href'] = G5_SHOP_URL."/item.php?it_id={$row['it_id']}";
			$list[$i]['datetime'] = $row['it_time'];
			$list[$i]['img'] = get_it_image($row['it_id'], $width, 0, true);
		}
		return $list;
	}
	
	private function latest_item_print($skin, $arr, $folder='latest') {
		global $tpl, $tpl_name;

		if(!$folder) $folder='latest';

		$tpl->define_template($folder,$skin,'latest.skin.html');
		if($this->header_title) $tpl->assign(array(
			'title' => $this->header_title,
		));
		$tpl->assign(array(
			'loop' => $arr,
		));

		$tpl->print_($tpl_name);
	}
}
?>