<?php
/* @var $models Personspeciality[] */

header('Content-type: application/ms-excel');
header('Content-Disposition: attachment; filename='.str_replace(' ', '_',$models[0]->SPEC).'.xls');


$data = array();
$data['quota'] = array();
$data['pzk'] = array();
$data['budget'] = array();
$data['contract'] = array();
$data['u'] = array();

$u_max = array();
$info_row = array();

$i = 0;
$qpzk = 0;
$u = 0;
foreach ($models as $model){
  if (!$i){
    $Speciality = $model->SPEC;
    $Faculty = $model->sepciality->facultet->FacultetFullName;
    $_contract_counter = $model->sepciality->SpecialityContractCount;
    $_budget_counter = $model->sepciality->SpecialityBudgetCount;
    $_pzk_counter = $model->sepciality->Quota1;
    $_quota_counter = $model->sepciality->Quota2;
    Personspeciality::setCounters($_contract_counter, $_budget_counter, $_pzk_counter, $_quota_counter);
  }
  $info_row['PIB'] = $model->NAME;
  $info_row['Points'] = $model->ComputedPoints;
  $info_row['isPZK'] = ($model->isOutOfComp || $model->Quota1)? '+': '';
  $info_row['isExtra'] = ($model->isExtraEntry)? '+': '';
  $info_row['isOriginal'] = (!$model->isCopyEntrantDoc)? '+': '';
  
  if ((Personspeciality::$is_rating_order) && $model->Quota1){
    //цільовики
    $was = Personspeciality::decrementCounter(Personspeciality::$C_QUOTA);    
    if ($was){
      Personspeciality::decrementCounter(Personspeciality::$C_BUDGET);
      $local_counter = 1 + $_quota_counter - $was;
      $data['quota'][$local_counter] = $info_row;
      $qpzk++;
    } else {
      $info_row['isPZK'] = 'Z';
      if ($u == 0){
        $u_max = $info_row;
      } else if ( (float)$u_max['Points'] < (float)$info_row['Points'] ){
        $u_max = $info_row;
      }
      $data['u'][$u++] = $info_row;
    }
  }

  if ((Personspeciality::$is_rating_order) && $model->isOutOfComp && !$model->Quota1){
    //поза конкурсом
    $was = Personspeciality::decrementCounter(Personspeciality::$C_OUTOFCOMPETITION);
    if ($was){
      Personspeciality::decrementCounter(Personspeciality::$C_BUDGET);
      $local_counter = 1 + $_pzk_counter - $was;
      $data['pzk'][$local_counter] = $info_row;
      $qpzk++;
    } else {
      $info_row['isPZK'] = 'Z';
      if ($u == 0){
        $u_max = $info_row;
      } else if ( (float)$u_max['Points'] < (float)$info_row['Points'] ){
        $u_max = $info_row;
      }
      $data['u'][$u++] = $info_row;
    }
  }

  if ( ( (Personspeciality::$is_rating_order) && $model->isBudget && !$model->isOutOfComp && !$model->Quota1 ) || 
          (!empty($data['u']) && !$model->isOutOfComp && !$model->Quota1 ) ){
    //на бюджет
    while (!empty($data['u']) && ( (float)$u_max['Points'] > (float)$info_row['Points'])){
      $was = Personspeciality::decrementCounter(Personspeciality::$C_BUDGET);
      if ($was){
        $local_counter = 1 + $_budget_counter - $was - $qpzk;
        $data['budget'][$local_counter] = $u_max;
      }
      $p_max = 0.0;
      foreach ($data['u'] as $u_id => $d_u){
        if ($d_u['PIB'] == $u_max['PIB'] && $d_u['Points'] == $u_max['Points']){
          unset($data['u'][$u_id]);
          continue;
        }
        if ((float)$d_u['Points'] > $p_max){
          $p_max = (float)$d_u['Points'];
          $u_max = $d_u;
        }
      }
    }
    $was = Personspeciality::decrementCounter(Personspeciality::$C_BUDGET);
    if ($was){
      $local_counter = 1 + $_budget_counter - $was - $qpzk;
      $data['budget'][$local_counter] = $info_row;
    }
  }

  if ((Personspeciality::$is_rating_order) && 
          ((!$model->isBudget && !$model->isOutOfComp && !$model->Quota1) || 
          (!$was && $model->isBudget && !$model->isOutOfComp && !$model->Quota1) )){
    //на контракт
    while (!empty($data['u']) && ( (float)$u_max['Points'] > (float)$info_row['Points'])){
      $was = Personspeciality::decrementCounter(Personspeciality::$C_BUDGET);
      if ($was){
        $local_counter = 1 + $_contract_counter - $was;
        $data['contract'][$local_counter] = $u_max;
      }
      $p_max = 0.0;
      foreach ($data['u'] as $u_id => $d_u){
        if ($d_u['PIB'] == $u_max['PIB'] && $d_u['Points'] == $u_max['Points']){
          unset($data['u'][$u_id]);
          continue;
        }
        if ((float)$d_u['Points'] > $p_max){
          $p_max = (float)$d_u['Points'];
          $u_max = $d_u;
        }
      }
    }
    $was = Personspeciality::decrementCounter(Personspeciality::$C_CONTRACT);
    if ($was){
      $local_counter = 1 + $_contract_counter - $was;
      $data['contract'][$local_counter] = $info_row;
    }
  }
  $i++;
}

?>
<HTML>
	<HEAD>
		<meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
		<title>РЕЙТИНГ</title>
		<style>
			TD {
				font-size: 9pt;
				padding: 3px;
				font-family: 'Tahoma';
				vertical-align: middle;
				border:solid 1px black;
			}
			H1 {
				font-size: 16pt;
			}
		</style>
	</HEAD>
	<BODY>
	<?php 
	?>

		<TABLE cellspacing="0" border="0" style="border-collapse:collapse;">
			<TR>
				<TD colspan='7' style="border:solid 0px black;">
					Факультет: <?php echo $Faculty;?>
				</TD>
			</TR>
			<TR>
				<TD colspan='7' style="border:solid 0px black;">
					Напрям підготовки: 
					<?php 
					echo $Speciality;
					?>
				</TD>
			</TR>
			<TR>
				<TD colspan='7' style="border:solid 0px black;">
					Ліцензійний обсяг: <?php echo $_contract_counter + $_budget_counter; ?>
				</TD>
			</TR>
			<TR>
				<TD colspan='7' style="border:solid 0px black;">
					Обсяг державного замовлення: <?php echo $_budget_counter; ?>
				</TD>
			</TR>
			<TR>
				<TD colspan='7' style="border:solid 0px black;">
					з них квота пільговиків: 
            <?php echo $_pzk_counter; ?>
          , квота цільовиків: 
            <?php echo $_quota_counter; ?>
				</TD>
			</TR>
			<TR>
				<TD>
					№ п/п
				</TD>
				<TD>
					ПІБ
				</TD>
				<TD>
					Бал
				</TD>
				<TD>
					Поза конкурс.
				</TD>
				<TD>
					Першочерг.
				</TD>
				<TD>
					Оригінал
				</TD>
				<TD>
					Зарах за хвилею
				</TD>
			</TR>
			
			
			<?php if (count($data['quota']) > 0){ ?>
			<TR>
				<TD colspan='7'>
					ЦІЛЬОВИЙ ПРИЙОМ 
				</TD>
			</TR>
			<?php } ?>
			
			<!-- Цільовики-->
			<?php for ($i = 1; $i < count($data['quota'])+1; $i++){ ?>
			<TR>
				<TD>
					<?php echo ($i);?>
				</TD>
				<TD>
					<?php echo $data['quota'][$i]['PIB'];?>
				</TD>
				<TD>
					<?php echo $data['quota'][$i]['Points'];?>
				</TD>
				<TD>
					<?php echo $data['quota'][$i]['isPZK'];?>
				</TD>
				<TD>
					<?php echo $data['quota'][$i]['isExtra'];?>
				</TD>
				<TD>
					<?php echo $data['quota'][$i]['isOriginal'];?>
				</TD>
				<TD>
					
				</TD>
			</TR>
			<?php } ?>
			
			
			<?php if (count($data['pzk']) > 0){ ?>
			<TR>
				<TD colspan='7'>
					ПОЗА КОНКУРСОМ
				</TD>
			</TR>
			<?php } ?>
			
			<!-- ПОЗА КОНКУРСОМ-->
			<?php for ($i = 1; $i < count($data['pzk'])+1; $i++){ ?>
			<TR>
				<TD>
					<?php echo ($i);?>
				</TD>
				<TD>
					<?php echo $data['pzk'][$i]['PIB'];?>
				</TD>
				<TD>
					<?php echo $data['pzk'][$i]['Points'];?>
				</TD>
				<TD>
					<?php echo $data['pzk'][$i]['isPZK'];?>
				</TD>
				<TD>
					<?php echo $data['pzk'][$i]['isExtra'];?>
				</TD>
				<TD>
					<?php echo $data['pzk'][$i]['isOriginal'];?>
				</TD>
				<TD>
					
				</TD>
			</TR>
			<?php } ?>
			
			
			<?php if (count($data['budget']) > 0){ ?>
			<TR>
				<TD colspan='7'>
					ДЕРЖ. ЗАМОВЛЕННЯ
				</TD>
			</TR>
			<?php } ?>
			
			<!-- ДЕРЖ. ЗАМОВЛЕННЯ-->
			<?php for ($i = 1; $i < count($data['budget'])+1; $i++){ ?>
			<TR>
				<TD>
					<?php echo ($i);?>
				</TD>
				<TD>
					<?php echo $data['budget'][$i]['PIB'];?>
				</TD>
				<TD>
					<?php echo $data['budget'][$i]['Points'];?>
				</TD>
				<TD>
					<?php echo $data['budget'][$i]['isPZK'];?>
				</TD>
				<TD>
					<?php echo $data['budget'][$i]['isExtra'];?>
				</TD>
				<TD>
					<?php echo $data['budget'][$i]['isOriginal'];?>
				</TD>
				<TD>
					
				</TD>
			</TR>
			<?php } ?>

			<?php if (count($data['contract']) > 0){ ?>
			<TR>
				<TD colspan='7'>
					ЗА КОНТРАКТОМ
				</TD>
			</TR>
			<?php } ?>
			
			<!-- ЗА КОНТРАКТОМ-->
			<?php for ($i = 1; $i < count($data['contract'])+1; $i++){ ?>
			<TR>
				<TD>
					<?php echo ($i);?>
				</TD>
				<TD>
					<?php echo $data['contract'][$i]['PIB'];?>
				</TD>
				<TD>
					<?php echo $data['contract'][$i]['Points'];?>
				</TD>
				<TD>
					<?php echo $data['contract'][$i]['isPZK'];?>
				</TD>
				<TD>
					<?php echo $data['contract'][$i]['isExtra'];?>
				</TD>
				<TD>
					<?php echo $data['contract'][$i]['isOriginal'];?>
				</TD>
				<TD>
					
				</TD>
			</TR>
			<?php } ?>
			
		</TABLE>
	</BODY>
</HTML>

<?php
	
?>