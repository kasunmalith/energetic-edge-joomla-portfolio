<?php 
  ini_set('display_errors', 0);   
//get joomla functions to the external file
define( '_JEXEC', 1 );
define( 'DS', DIRECTORY_SEPARATOR );
$fullpath=$_SERVER[ 'PHP_SELF' ];
$path= explode("modules", $fullpath);
 $path= $path[0];
$d_path=$_SERVER[ 'DOCUMENT_ROOT' ].$path;
define( 'JPATH_BASE', $d_path );
require_once( JPATH_BASE . DS . 'includes' . DS . 'defines.php' );
require_once( JPATH_BASE . DS . 'includes' . DS . 'framework.php' );
require_once( JPATH_BASE . DS . 'libraries' . DS . 'joomla' . DS . 'factory.php' );
$mainframe = JFactory::getApplication('site');
$mainframe->initialise();
$session = JFactory::getSession();
//end get joomla functions to the external file
/*
 $jpathbase=JPATH_BASE;
 $jpathbase=str_replace('/', '\\', $jpathbase);
  $jpathbase=str_replace('\\\\', '\\', $jpathbase);
 $myMod_dir = str_replace($jpathbase, '', dirname(__FILE__));
 $myMod_dir=str_replace('\\', '/', $myMod_dir);
 $myMod_dir;

 */
 $myMod_dir = str_replace(JPATH_BASE, '', dirname(__FILE__));
$myMod_dir=str_replace('\\', '/', $myMod_dir);


 
function subMenuLoad($level,$parentid){
$db = JFactory::getDbo();
$query = $db->getQuery(true);
$query->select(array('id,title,level,parent_id,path'));
$query->from('#__menu');
$query->where("menutype='mainmenu' and published=1 and parent_id={$parentid}");
$query->order('ordering ASC');
$db->setQuery($query); 
return $db;
}
function backMenuLoad($level,$parentid){
$db = JFactory::getDbo();
$query = $db->getQuery(true);
$query->select(array('id,title,level,parent_id'));
$query->from('#__menu');
$query->where("menutype='mainmenu' and published=1 and id={$parentid}");
$query->order('ordering ASC');
$db->setQuery($query); 
return $db;
}
function hasChild($level,$id){
      $db1=subMenuLoad($level,$id);
        $menu_ids = $db1->loadResultArray(0);
        $menucount=count($menu_ids);
        return $menucount>0;
}
 $this_level = JRequest::getVar('this_level');
   --$this_level;
    $menuid = JRequest::getVar('menuid');
      $type = JRequest::getVar('type');
    if($type=="back"){
         if(isset($this_level)&&isset($menuid)){
               $backmenuid=$menuid;
              $db=backMenuLoad($this_level,$menuid);
              $parent_ids = $db->loadResultArray(3);
            
            $parent_id=$parent_ids[0];
            
          $db=subMenuLoad($this_level,$parent_id);
         $titles= $db->loadResultArray(1);
            $parentid=$menuid;
          $menu_ids = $db->loadResultArray(0);
    $titles = $db->loadResultArray(1);
    $levels=$db->loadResultArray(2);
      $articlepaths=$db->loadResultArray(4);
    $id=0;
if($this_level==1){}else{
    ?>    
     <a  title="<?php echo $parent_id; ?>" class="back" id="back<?php echo $parent_id; ?>"  href="javascript:void(0)" onclick="backMenu(<?php echo $parent_id; ?>,<?php echo $this_level; ?>,'<?php echo $myMod_dir; ?>')">
        
     </a>  
    <?php
    }
    foreach($titles as $title){
          if($id%12==0){
        ?>
        <ul class="sub-temp">
        <?php
        }
          
        $newLevel= ++$levels[$id];
    if(hasChild($newLevel,$menu_ids[$id])){
         ?>
          <li class="hover_blink" id="menu<?php echo $menu_ids[$id]; ?>"  ><a title="<?php echo $menu_ids[$id]; ?>"  onclick="loadAjaxMenu(<?php echo $menu_ids[$id]; ?>,<?php echo $levels[$id] ?>,'<?php echo $myMod_dir; ?>',1)" href="javascript:void(0)"><?php echo $title;?></a>
   
              <?php
    }else{
       $articlepath=$articlepaths[$id]; 
  ?>
    
    <li id="menu<?php echo $menu_ids[$id]; ?>" class="hover_blink"  ><a title="<?php echo $menu_ids[$id]; ?>"  onclick="loadAjaxMenu(<?php echo $menu_ids[$id]; ?>,<?php echo $levels[$id] ?>,'<?php echo $myMod_dir; ?>',1)" href="<?php echo $articlepath; ?>"><?php echo $title;?></a>
        <?php
       
   }
if(hasChild($newLevel,$menu_ids[$id])){
            ?> 
            <ul style="display: none" id="sub-menu<?php echo $menu_ids[$id]; ?>"></ul>
        </li>
            <?php
        }
        $id++;
        if($id%12==0){
        ?>
        </ul><!--.sub-temp -->
        <?php
        }
    } //foreach end
    if($id%12!=0){
?>
 </ul><!--.sub-temp -->
<?php
 }
         }
////////////////////// END BACK
    }else{
   $backmenuid=$menuid;
    if(isset($this_level)&&isset($menuid)){ 
    $db=subMenuLoad($this_level,$menuid);
    $parentid=$menuid;
    $menu_ids = $db->loadResultArray(0);
    $titles = $db->loadResultArray(1);
    $levels=$db->loadResultArray(2);
     $articlepaths=$db->loadResultArray(4);
    $id=0;
    if($this_level<=1){}else{
    ?>   
     <a title="<?php echo $backmenuid; ?>"  class="back" id="back<?php echo $backmenuid; ?>" href="javascript:void(0)" onclick="backMenu(<?php echo $backmenuid; ?>,<?php echo $this_level; ?>,'<?php echo $myMod_dir; ?>')"></a>   
    <?php
    }
    foreach($titles as $title){
        if($id%12==0){
        ?>
        <ul class="sub-temp">
        <?php
        }
        $newLevel= ++$levels[$id];
    if(hasChild($newLevel,$menu_ids[$id])){
         ?>
          <li id="menu<?php echo $menu_ids[$id]; ?>"  class="hover_blink"><a title="<?php echo $menu_ids[$id]; ?>"  onclick="loadAjaxMenu(<?php echo $menu_ids[$id]; ?>,<?php echo $levels[$id] ?>,'<?php echo $myMod_dir; ?>',1)" href="javascript:void(0)"><?php echo $title;?></a>
   
              <?php
    }else{
       $articlepath=$articlepaths[$id]; 
  ?>
    
    <li id="menu<?php echo $menu_ids[$id]; ?>"  class="hover_blink"><a title="<?php echo $menu_ids[$id]; ?>"  onclick="loadAjaxMenu(<?php echo $menu_ids[$id]; ?>,<?php echo $levels[$id] ?>,'<?php echo $myMod_dir; ?>',1)" href="<?php echo $articlepath; ?>"><?php echo $title;?></a>
        <?php
       
   }
 if(hasChild($newLevel,$menu_ids[$id])){
            ?>       
            <ul style="display: none" id="sub-menu<?php echo $menu_ids[$id]; ?>"></ul>
        </li>
            <?php
        }
        $id++;
         if($id%12==0){
        ?>
        </ul><!--.sub-temp -->
        <?php
        }
        
    } 
    //foreach end
 if($id%12!=0){
?>
 </ul><!--.sub-temp -->
<?php
 }
    
    }else{  
    }
    }
    
?>
