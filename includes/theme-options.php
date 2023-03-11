<?php
add_action ( 'admin_menu', 'beauty_theme_options' );
function beauty_theme_options() {
	add_theme_page ( '主题设置', '主题设置', 'edit_themes', basename ( __FILE__ ), 'beauty_theme_options_page' );
}

function beauty_theme_options_page() {
	if (! empty ( $_POST ['submit'] ) ) {
		$options_field = array(
				'description',
				'keyword', 
				'footer_pages',
				'statistics_code',
				'is_toc',
				'is_tag_link',
				'is_city',
				'toc_count',
				'sns',
				'is_comment_pass',
				'is_comment_smilies',
				'is_mobile_nav',
				'is_web_pjax',
		);
		$options = array();
		foreach ( $_POST as $field => $value ) {
			if ( isset($value) && in_array( $field, $options_field ) ) {
				$options [$field] = $value;
			}
		}
		$res = update_option( 'beauty_options', $options );
		if ($res) {
			echo '<div class="updated"><p><strong>保存成功.</strong></p></div>';
		} else {
			echo '<div class="updated"><p><strong>没有修改任何设置.</strong></p></div>';
		}
	}
	$options = get_option( 'beauty_options' );
?>
<div class="wrap">
	<?php screen_icon(); ?><h2>主题设置</h2>
	<div class="beauty-main">
		<div class="beauty-menu">
			<ul>
				<li><a href="javascript:;" class="option_basic">基本设置</a></li>
				<li><a href="javascript:;" class="option_sns">社交设置</a></li>
			</ul>
		</div>
		<div class="beauty-setting-page">
			<form method="post">
			<?php settings_fields( 'beauty-setting-group' ); ?>
				<div id="option_basic" class="option-panle">
					<table>						
						<tr valign="top">
							<th scope="row">站点关键词:</th>
							<td>
								<textarea name="keyword" id="keyword" class="large-text code" rows="2" cols="80"><?php echo $options ['keyword']; ?></textarea>	
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">站点描述:</th>
							<td>
								<textarea name="description" id="description" class="large-text code" rows="3" cols="120"><?php echo $options ['description']; ?></textarea>	
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">底部文字链接:</th>
							<td>
								<textarea name="footer_pages" id="footer_pages" class="large-text code" rows="3" cols="120"><?php echo stripcslashes ($options ['footer_pages']) ; ?></textarea>	
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">网站统计代码:</th>
							<td>
								<textarea name="statistics_code" id="statistics_code" class="large-text code" rows="5" cols="120"><?php echo stripcslashes ($options ['statistics_code']); ?></textarea>	
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">是否开启移动端导航菜单:</th>
							<td>
								<p>									
									<label><input type="radio" name="is_mobile_nav" value="N" <?php echo empty( $options ['is_mobile_nav'] ) || $options ['is_mobile_nav'] == 'N' ? 'checked="checked"' : null; ?> /> 否 </label>
									<label><input type="radio" name="is_mobile_nav" value="Y" <?php echo $options ['is_mobile_nav'] == 'Y' ? 'checked="checked"' : null; ?> /> 是 </label> &nbsp; 
								</p>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">是否开启全站Pjax:</th>
							<td>
								<p>
									<label><input type="radio" name="is_web_pjax" value="N" <?php echo $options ['is_web_pjax'] == 'N' ? 'checked="checked"' : null; ?> /> 否</label> 
									<label><input type="radio" name="is_web_pjax" value="Y" <?php echo empty( $options ['is_web_pjax'] ) || $options ['is_web_pjax'] == 'Y' ? 'checked="checked"' : null; ?> /> 是 </label> &nbsp; 
								</p>
							</td>
						</tr>						
						<tr valign="top">
							<th scope="row">是否开启评论表情:</th>
							<td>
								<p>
									<label><input type="radio" name="is_comment_smilies" value="N" <?php echo $options ['is_comment_smilies'] == 'N' ? 'checked="checked"' : null; ?> /> 否</label> 
									<label><input type="radio" name="is_comment_smilies" value="Y" <?php echo empty( $options ['is_comment_smilies'] ) || $options ['is_comment_smilies'] == 'Y' ? 'checked="checked"' : null; ?> /> 是 </label> &nbsp; 
								</p>
							</td>
						</tr>						
						<tr valign="top">
							<th scope="row">是否开启评论通过通知评论者:</th>
							<td>
								<p>
									<label><input type="radio" name="is_comment_pass" value="N" <?php echo $options ['is_comment_pass'] == 'N' ? 'checked="checked"' : null; ?> /> 否</label> 
									<label><input type="radio" name="is_comment_pass" value="Y" <?php echo empty( $options ['is_comment_pass'] ) || $options ['is_comment_pass'] == 'Y' ? 'checked="checked"' : null; ?> /> 是 </label> &nbsp; 
								</p>
							</td>
						</tr>
					</table>
				</div>
				
				<div id="option_sns" class="option-panle">
					<table>
						<tr valign="top">
							<th scope="row">Facebook:</th>
							<td>
								<input name="sns[facebook]" type="text" value="<?php echo $options ['sns']['facebook']; ?>" class="regular-text" />	
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">Twitter:</th>
							<td>
								<input name="sns[twitter]" type="text" value="<?php echo $options ['sns']['twitter']; ?>" class="regular-text" />	
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">Google+:</th>
							<td>
								<input name="sns[google]" type="text" value="<?php echo $options ['sns']['google']; ?>" class="regular-text" />	
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">Github:</th>
							<td>
								<input name="sns[github]" type="text" value="<?php echo $options ['sns']['github']; ?>" class="regular-text" />	
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">Pinterest:</th>
							<td>
								<input name="sns[pinterest]" type="text" value="<?php echo $options ['sns']['pinterest']; ?>" class="regular-text" />	
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">Linkedin:</th>
							<td>
								<input name="sns[linkedin]" type="text" value="<?php echo $options ['sns']['linkedin']; ?>" class="regular-text" />	
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">Instagram:</th>
							<td>
								<input name="sns[instagram]" type="text" value="<?php echo $options ['sns']['instagram']; ?>" class="regular-text" />	
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">新浪微博:</th>
							<td>
								<input name="sns[weibo]" type="text" value="<?php echo $options ['sns']['weibo']; ?>" class="regular-text" />	
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">邮箱地址:</th>
							<td>
								<input name="sns[email]" type="text" value="<?php echo $options ['sns']['email']; ?>" class="regular-text" />									
							</td>
						</tr>	
						<tr valign="top">
							<th scope="row">Rss:</th>
							<td>
								<input name="sns[rss]" type="text" value="<?php echo $options ['sns']['rss']; ?>" class="regular-text" />	
							</td>
						</tr>
					</table>
				</div>
				<input type="hidden" name="is_verify" value="<?php echo $options ['is_verify']; ?>" />
				<div class="btn-save"><input type="submit" class="button-primary" name="submit" value="保存设置" /></div>
				<div class="btn-save"><input type="submit" class="button-primary" name="submit" value="重置设置" /></div>
			</form>
		</div>
		<div class="clr"></div>
	</div>
</div>

<style type="text/css">
	.clr { clear: both; }
	.btn-save { margin:10px 10px 10px 0px; float:left; }
	.beauty-main { margin-top: 30px; padding: 20px; background-color: #fafafa;width: 860px;border-color: #eee #eee #eee #333333;
    border-image: none;
    border-style: solid;
    border-width: 1px 1px 1px 4px;
    box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.1);}
	.beauty-main a { text-decoration: none; }
	.beauty-menu, .beauty-setting-page { float: left; }
	.beauty-menu { margin-right: 20px; }
	.beauty-menu li, .beauty-menu a { display: block; }
	.beauty-menu li { border-bottom: 1px solid #f0f0f0; margin: 0; }
	.beauty-menu a { padding: 10px 10px; color:#333333;}
	.beauty-menu a:focus {box-shadow: 0 0 0 1px #333333;color: #333333;}
	.beauty-menu a:active ,.beauty-menu a:hover{color: #c33;}
	.beauty-setting-page { border-left: 1px solid #e3e3e3; padding-left: 20px; }
	.beauty-setting-page .option-panle { display: none; }
	.beauty-main .right { text-align: right; }
	.beauty-main table { background-color: #e3e3e3; margin: 10px 0; border: 1px solid #e3e3e3;  border-spacing: 0; border-collapse: collapse; max-width: 745px; width: 100%; }
	.beauty-main table td, .beauty-main table th { background-color: #fff; border: 1px solid #e3e3e3; padding: 5px 10px; vertical-align: middle; white-space: nowrap; }
	.beauty-main table th { font-weight: normal; background-color: #fff; text-align: right; }
	.beauty-main table > tbody > tr:nth-child(odd) > td, .beauty-main table tbody > tr:nth-child(odd) > th {  background-color: #f9f9f9; }	
	#option_basic { display: block; }
</style>

<script type="text/javascript">
jQuery(function($){
	$('.beauty-menu li a').click(function(){
		var id = $(this).attr('class');
		$('.option-panle').slideUp('fast');
		$('#'+id).slideDown('fast');
	});
});
</script>
<?php } ?>