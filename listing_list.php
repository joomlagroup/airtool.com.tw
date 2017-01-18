<?php
/**
 * @package	HikaShop for Joomla!
 * @version	2.3.2
 * @author	hikashop.com
 * @copyright	(C) 2010-2014 HIKARI SOFTWARE. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?> 
<?php

if(!function_exists('add_hikashop_childcat')){
    function add_hikashop_childcat($item, $that, $actives){
        if(!empty($item->childs)){
            $app = JFactory::getApplication();
            $only_if_products = $that->params->get('only_if_products',0);
        ?>

                <ul class="hikashop_category_list<?php echo $that->params->get('ul_class_name'); ?>">
                    <?php
                    $limit = $that->params->get('child_limit');
                    $i = 0;
                    foreach($item->childs as $child){
                        if($only_if_products && $child->number_of_products<1) continue;
                        if(!empty($limit) && $i >= $limit) break;
                        $i++;
                        $link = $that->getLink($child);
                        $class = '';
                        if(in_array($child->category_id, $actives)) $class=' current active';
                        ?>
                        <li class="hikashop_category_list_item<?php echo $class; ?> <?php if(!empty($child->childs)) echo 'parent'?>">
							
							<a href="<?php echo $link; ?>">
                                <?php
                                echo $child->category_name;
                                if($that->params->get('number_of_products',0)){
                                    echo ' ('.$child->number_of_products.')';
                                }
                                ?>
                            </a>
 							<?php
							if(!empty($child->childs)){
							?>
							<span class="showsubmenu icon-angle-down"></span>
							<?php 
							add_hikashop_childcat($child, $that, $actives);?>  
							<?php } ?>
                        </li>
                    <?php }?>
                </ul>

        <?php }
    }
}
if(!function_exists('get_hikashop_activecat')){
    function get_hikashop_activecat(&$actives, $db, $cid=''){
        if(JRequest::getString('option')==HIKASHOP_COMPONENT && in_array(JRequest::getString('ctrl','category'),array('category','product'))){
            if($cid == ''){
                if(JRequest::getString('ctrl','category')=='product'&&JRequest::getString('task','listing')=='show'){
                    $app = JFactory::getApplication();
                    $found = $app->getUserState(HIKASHOP_COMPONENT.'.last_category_selected');
                    $config =& hikashop_config();
	
                    $pathway_sef_name = $config->get('pathway_sef_name','category_pathway');
                    $cid = JRequest::getInt($pathway_sef_name,0);
                }else{
                    $cid = JRequest::getInt('cid',0);
                };
				
                $actives[] = $cid;
            }
            if($cid){
                $db->setQuery('SELECT category_parent_id FROM #__hikashop_category WHERE category_id='.$cid);
                $result = $db->loadResult();
                if($result){
                    $actives[] = $result;
                    get_hikashop_activecat($actives, $db, $result);
                }
            }
        }
    }
}
$actives = array();
get_hikashop_activecat($actives, JFactory::getDbo());
?>

<?php
if(!empty($this->rows)){
	$pagination = $this->config->get('pagination','bottom');
	if(in_array($pagination,array('top','both')) && $this->params->get('show_limit') && $this->pageInfo->elements->total > $this->pageInfo->limit->value){ $this->pagination->form = '_top'; ?>
	<form action="<?php echo hikashop_currentURL();?>" method="post" name="adminForm_<?php echo $this->params->get('main_div_name').$this->category_selected;?>_top">
		<div class="hikashop_subcategories_pagination hikashop_subcategories_pagination_top">
		<?php echo $this->pagination->getListFooter($this->params->get('limit')); ?>
		<span class="hikashop_results_counter"><?php echo $this->pagination->getResultsCounter(); ?></span>
		</div>
		<input type="hidden" name="filter_order_<?php echo $this->params->get('main_div_name').$this->category_selected;?>" value="<?php echo $this->pageInfo->filter->order->value; ?>" />
		<input type="hidden" name="filter_order_Dir_<?php echo $this->params->get('main_div_name').$this->category_selected;?>" value="<?php echo $this->pageInfo->filter->order->dir; ?>" />
		<?php echo JHTML::_( 'form.token' ); ?>
	</form>
	<?php } ?>
	<div class="hikashop_subcategories">
	<?php

	$only_if_products = $this->params->get('only_if_products',0);
	switch($this->params->get('child_display_type')){
		case 'nochild':
		default:
			if(!empty($this->rows)){
			?>
				<ul class="hikashop_category_list<?php echo $this->params->get('ul_class_name'); ?>">
				<?php

					$width = (int)(100/$this->params->get('columns'));
					if(empty($width)){
						$width='';
					}else{
						$width='style="width:'.$width.'%;"';
					}
					$app = JFactory::getApplication();
					$found = '';
					if(JRequest::getString('option')==HIKASHOP_COMPONENT && in_array(JRequest::getString('ctrl','category'),array('category','product'))){
						foreach($this->rows as $row){
							if(JRequest::getString('ctrl','category')=='product'&&JRequest::getString('task','listing')=='show'){
								$found = $app->getUserState(HIKASHOP_COMPONENT.'.last_category_selected');
								$config =& hikashop_config();
								$pathway_sef_name = $config->get('pathway_sef_name','category_pathway');
								$cid = JRequest::getInt($pathway_sef_name,0);
							}else{
								$cid = JRequest::getInt('cid',0);
							}
							if($cid == $row->category_id){
								$found=$row->category_id;
								$app->setUserState(HIKASHOP_COMPONENT.'.last_category_selected',$row->category_id);
								break;
							}
						}
					}
					foreach($this->rows as $row){
						if($only_if_products && $row->number_of_products<1) continue;
						$link = $this->getLink($row);
						$class = '';
						if($found == $row->category_id){
								$class=' current active';
						}
						?>
						<li class="hikashop_category_list_item<?php echo $class; ?>" <?php echo $width; ?>>
							<a href="<?php echo $link; ?>" >
							<?php
								echo $row->category_name;
								if($this->params->get('number_of_products',0)){
									echo ' ('.$row->number_of_products.')';
								}
							 ?>
							</a>
						</li>
						<?php
					}
				?>
				</ul>
		<?php
			}
			break;
		case 'allchildsexpand':
			?>
			<ul id="category_panel_<?php echo $this->params->get('id');?>" class="menu-sliders">
			<?php
			if(!empty($this->rows)){
				foreach($this->rows as $k => $row){
					if($only_if_products && $row->number_of_products<1) continue;
					$link = $this->getLink($row);
                    $class = '';
					
                    if(in_array($row->category_id, $actives)) $class=' current active';
					?>
					<li class="<?php if(!empty($row->childs)) echo 'parent'?><?php echo $class; ?>">
								<span class="showsubmenu icon-angle-down"></span>
								<a href="<?php echo $link;?>" id="category_pane_<?php echo $k;?>">
									<?php
										echo $row->category_name;
										if($this->params->get('number_of_products',0)){
											echo ' ('.$row->number_of_products.')';
										}
									?>
								</a>

                        <?php add_hikashop_childcat($row, $this, $actives);?>
					</li>
                <?php }?>
            <?php }?>
			</ul>
			
			
            <?php
			break;
		case 'allchilds':
			jimport('joomla.html.pane');
			$found = -1;
			if(JRequest::getString('option')==HIKASHOP_COMPONENT && in_array(JRequest::getString('ctrl','category'),array('category','product')) && $cid = JRequest::getInt('cid',0)){
				if(JRequest::getString('ctrl','category')=='product'&&JRequest::getString('task','listing')=='show'){
					$config =& hikashop_config();
					$pathway_sef_name = $config->get('pathway_sef_name','category_pathway');
					$cid = JRequest::getInt($pathway_sef_name,0);
					if(empty($cid)){
						$database = JFactory::getDBO();
						$query = "SELECT category_id FROM ".hikashop_table('product_category').' WHERE product_id='.hikashop_getCID('product_id').' ORDER BY product_category_id ASC';
						$database->setQuery($query);
						$cid = $database->loadResult();
						if(empty($cid)){
							$class=hikashop_get('class.product');
							$product = $class->get(hikashop_getCID('product_id'));
							if($product && $product->product_type=='variant' && $product->product_parent_id){
								$query = "SELECT category_id FROM ".hikashop_table('product_category').' WHERE product_id='.$product->product_parent_id.' ORDER BY product_category_id ASC';
								$database->setQuery($query);
								$cid = $database->loadResult();
							}
						}
					}
				}
				$i=0;
				if(!empty($this->rows)){
					foreach($this->rows as $k => $row){
						if($only_if_products && $row->number_of_products<1) continue;
						if($row->category_id==$cid){
							$found = $i;
							break;
						}
						if(!empty($row->childs)){
							foreach($row->childs as $child){
								if($child->category_id==$cid){
									$found = $i;
									break 2;
								}
							}
						}
						$i++;
					}
					$app = JFactory::getApplication();
					if($found>=0){
						$app->setUserState(HIKASHOP_COMPONENT.'.last_category_selected',$found);
					}elseif(JRequest::getString('ctrl','category')!='category'||JRequest::getString('task','listing')!='listing'){
						$found = (int)$app->getUserState(HIKASHOP_COMPONENT.'.last_category_selected');
					}
				}

			}else{
				$cid = 0;
			}

			$this->tabs = hikashop_get('helper.sliders');
			$this->tabs->setOptions(array('startOffset'=>$found,'startTransition'=>0,'displayFirst'=>0));
			echo $this->tabs->startPane( 'category_panel_'.$this->params->get('id'));
			if(!empty($this->rows)){
				foreach($this->rows as $k => $row){
					if($only_if_products && $row->number_of_products<1) continue;
					if($this->params->get('number_of_products',0)){
						$row->category_name.= ' ('.$row->number_of_products.')';
					}

					if( !$this->module || $this->params->get('links_on_main_categories')){
						$link = $this->getLink($row);
						$row->category_name = '<a href="'.$link.'">'.$row->category_name.'</a>';
					}

					$toOpen = false;
					if($row->category_id == hikashop_getCid())
						$toOpen = true;
					if(!empty($row->childs)){
						foreach($row->childs as $child){
							if($child->category_id == hikashop_getCid())
								$toOpen = true;
						}
					}

					echo $this->tabs->startPanel($row->category_name, 'category_pane_'.$k, !empty($row->childs), $toOpen);
					if(!empty($row->childs)){
						?><ul class="hikashop_category_list<?php echo $this->params->get('ul_class_name'); ?>"><?php
							foreach($row->childs as $child){
								if($only_if_products && $child->number_of_products<1) continue;
								$class = '';
								if($cid==$child->category_id){
									$class=' current active';
								}
								$link = $this->getLink($child);
								?>
								<li class="hikashop_category_list_item<?php echo $class; ?>">
									<a class="hikashop_category_list_item_link" href="<?php echo $link; ?>">
									<?php
										echo $child->category_name;
										if($this->params->get('number_of_products',0)){
											echo ' ('.$child->number_of_products.')';
										}
									?>
									</a>
								</li>
								<?php
							}
						?></ul><?php
					} else {
						echo JText::_('HIKA_LISTING_LIST_EMPTY');
					}
					echo $this->tabs->endPanel();
				}
			}
			echo $this->tabs->endPane();

			break;
	}
	?>
	</div>
	<?php if(in_array($pagination,array('bottom','both')) && $this->params->get('show_limit') && $this->pageInfo->elements->total > $this->pageInfo->limit->value){ $this->pagination->form = '_bottom'; ?>
	<form action="<?php echo hikashop_currentURL();?>" method="post" name="adminForm_<?php echo $this->params->get('main_div_name').$this->category_selected;?>_bottom">
		<div class="hikashop_subcategories_pagination hikashop_subcategories_pagination_bottom">
		<?php echo $this->pagination->getListFooter($this->params->get('limit')); ?>
		<span class="hikashop_results_counter"><?php echo $this->pagination->getResultsCounter(); ?></span>
		</div>
		<input type="hidden" name="filter_order_<?php echo $this->params->get('main_div_name').$this->category_selected;?>" value="<?php echo $this->pageInfo->filter->order->value; ?>" />
		<input type="hidden" name="filter_order_Dir_<?php echo $this->params->get('main_div_name').$this->category_selected;?>" value="<?php echo $this->pageInfo->filter->order->dir; ?>" />
		<?php echo JHTML::_( 'form.token' ); ?>
	</form>
	<?php }
} ?>

