<?php

class paginate
{
	private $db;
	
	function __construct($DB_con)
	{
		$this->db = $DB_con;
	}
	
	public function dataViewDinamic($query, $id, $caption){
		// Remove o ponto e vírgula da query da view
		$query = str_replace(';', '', $query);
		$result = $this->db->query($query);
		$row = $result->fetch(PDO::FETCH_ASSOC);
		// die('<pre>'.print_r($row, 1));

		// Trata o Warning: Invalid argument supplied for foreach() in /var/www/html/afd/consulta-afd-siape/classes/functions.php on line 97
		if(!empty($row)) {
?>
		<div class="row">
		<table class="table table-condensed table-bordered table-striped table-hover" style="margin-bottom: -12px; font-size: 12px;">
			<caption>
				<strong><? echo $caption ?><strong>
			</caption>
		<thead>
		<tr class="info">
<?php
			foreach ($row as $field => $value){
				echo "<th>$field</th>";
			}
			echo "</tr>
			</thead>
			<tbody>";
			$data = $this->db->query($query);
			$data->setFetchMode(PDO::FETCH_ASSOC);
			foreach($data as $row){
				echo "<tr>";
				foreach ($row as $name=>$value){
					echo "<td>$value</td>";
				}
			echo "</tr>";
			}
			echo "</tbody>
			</table>
			</div>";
		} else {
?>
			<br>
			<div class="alert alert-info alert-dismissible" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hsearchden="true">&times;</span></button>
				<span class="glyphicon glyphicon-exclamation-sign" aria-hsearchden="true"></span>
				<strong>Informação!</strong> 
				<br>Registro não encontrado!
			</div>
<?php
		}
	
	}
	
	public function paging($query,$records_per_page)
	{
		$starting_position=0;
		if(isset($_GET["page_no"]))
		{
			$starting_position=($_GET["page_no"]-1)*$records_per_page;
		}
		// MySQL
		// $query2=$query." limit $starting_position,$records_per_page";
		
		// Postgres
		$query2=$query." OFFSET $starting_position LIMIT $records_per_page";
		return $query2;
	}
	
	public function pagingLinkSearch($query,$records_per_page,$search,$max_links_page)
	{
		$self = $_SERVER['PHP_SELF'];
		
		$stmt = $this->db->prepare($query);
		$stmt->execute();
		
		$total_no_of_records = $stmt->rowCount();
		
		if($total_no_of_records > 0)
		{
			$total_no_of_pages=ceil($total_no_of_records/$records_per_page);
			$current_page=1;
			if(isset($_GET["page_no"]))
			{
				$current_page=$_GET["page_no"];
			}
			echo "<nav aria-label=\"Page navigation\">
			      <ul class=\"pagination pagination-sm\">";
					if($current_page!=1)
					{
						$previous =$current_page-1;
						echo "<li><a href='".$self."?search=".$search."&page_no=1'>Primeiro</a></li>";
						echo "<li><a href='".$self."?search=".$search."&page_no=".$previous."'>Anterior</a></li>";
					}
					
					$start_links_page  = $current_page - $max_links_page;
					$finish_links_page = $current_page + $max_links_page;

					if ($start_links_page <= $max_links_page) {
						$start_links_page  = 1;
					}
					if ($finish_links_page >= $total_no_of_pages) {
						$finish_links_page  = $total_no_of_pages;
					}		
					
					for($i=$start_links_page;$i<=$finish_links_page;$i++)
					{
						if($i==$current_page)
						{
							echo "<li class=\"active\"><a href='".$self."?search=".$search."&page_no=".$i."'>".$i."</a></li>";
						}
						else
						{
							echo "<li><a href='".$self."?search=".$search."&page_no=".$i."'>".$i."</a></li>";
						}
					}
					
					if($current_page!=$total_no_of_pages)
					{
						$next=$current_page+1;
						echo "<li><a href='".$self."?search=".$search."&page_no=".$next."'>Próximo</a></li>";
						echo "<li><a href='".$self."?search=".$search."&page_no=".$total_no_of_pages."'>Último</a></li>";
					}
				echo "<li class=\"next\"><a href=\"#\">Total de registros: <strong>$total_no_of_records</strong></a></li>";
				echo "</ul>
			      </nav>";
		}
	}

	public function pagingLinkSearchType($query,$records_per_page,$search,$type,$max_links_page)
	{
		$self = $_SERVER['PHP_SELF'];
		
		$stmt = $this->db->prepare($query);
		$stmt->execute();
		
		$total_no_of_records = $stmt->rowCount();
		
		if($total_no_of_records > 0)
		{
			$total_no_of_pages=ceil($total_no_of_records/$records_per_page);
			$current_page=1;
			if(isset($_GET["page_no"]))
			{
				$current_page=$_GET["page_no"];
			}
			echo "<nav aria-label=\"Page navigation\">
			      <ul class=\"pagination pagination-sm\">";
					if($current_page!=1)
					{
						$previous =$current_page-1;
						echo "<li><a href='".$self."?search=".$search."&type=".$type."&page_no=1'>Primeiro</a></li>";
						echo "<li><a href='".$self."?search=".$search."&type=".$type."&page_no=".$previous."'>Anterior</a></li>";
					}
					
					$start_links_page  = $current_page - $max_links_page;
					$finish_links_page = $current_page + $max_links_page;

					if ($start_links_page <= $max_links_page) {
						$start_links_page  = 1;
					}
					if ($finish_links_page >= $total_no_of_pages) {
						$finish_links_page  = $total_no_of_pages;
					}		
					
					for($i=$start_links_page;$i<=$finish_links_page;$i++)
					{
						if($i==$current_page)
						{
							echo "<li class=\"active\"><a href='".$self."?search=".$search."&type=".$type."&page_no=".$i."'>".$i."</a></li>";
						}
						else
						{
							echo "<li><a href='".$self."?search=".$search."&type=".$type."&page_no=".$i."'>".$i."</a></li>";
						}
					}
					
					if($current_page!=$total_no_of_pages)
					{
						$next=$current_page+1;
						echo "<li><a href='".$self."?search=".$search."&type=".$type."&page_no=".$next."'>Próximo</a></li>";
						echo "<li><a href='".$self."?search=".$search."&type=".$type."&page_no=".$total_no_of_pages."'>Último</a></li>";
					}
				echo "<li class=\"next\"><a href=\"#\">Total de registros: <strong>$total_no_of_records</strong></a></li>";
				echo "</ul>
			      </nav>";
		}
	}
	
	// http://10.209.9.131/consulta-afd-siape-dev/app/search-interval.php?date_in=20171002&date_out=20171002&page_no=3
	public function pagingLinkSearchDate($query,$records_per_page,$date_in,$date_out,$max_links_page)
	{
		$self = $_SERVER['PHP_SELF'];
		
		$stmt = $this->db->prepare($query);
		$stmt->execute();
		
		$total_no_of_records = $stmt->rowCount();
		
		if($total_no_of_records > 0)
		{
			$total_no_of_pages=ceil($total_no_of_records/$records_per_page);
			$current_page=1;
			if(isset($_GET["page_no"]))
			{
				$current_page=$_GET["page_no"];
			}
			echo "<nav aria-label=\"Page navigation\">
			      <ul class=\"pagination pagination-sm\">";
					if($current_page!=1)
					{
						$previous =$current_page-1;
						echo "<li><a href='".$self."?date_in=".$date_in."&date_out=".$date_out."&page_no=1'>Primeiro</a></li>";
						echo "<li><a href='".$self."?date_in=".$date_in."&date_out=".$date_out."&page_no=".$previous."'>Anterior</a></li>";
					}
					
					$start_links_page  = $current_page - $max_links_page;
					$finish_links_page = $current_page + $max_links_page;

					if ($start_links_page <= $max_links_page) {
						$start_links_page  = 1;
					}
					if ($finish_links_page >= $total_no_of_pages) {
						$finish_links_page  = $total_no_of_pages;
					}		
					
					for($i=$start_links_page;$i<=$finish_links_page;$i++)
					{
						if($i==$current_page)
						{
							echo "<li class=\"active\"><a href='".$self."?date_in=".$date_in."&date_out=".$date_out."&page_no=".$i."'>".$i."</a></li>";
						}
						else
						{
							echo "<li><a href='".$self."?date_in=".$date_in."&date_out=".$date_out."&page_no=".$i."'>".$i."</a></li>";
						}
					}
					
					if($current_page!=$total_no_of_pages)
					{
						$next=$current_page+1;
						echo "<li><a href='".$self."?date_in=".$date_in."&date_out=".$date_out."&page_no=".$next."'>Próximo</a></li>";
						echo "<li><a href='".$self."?date_in=".$date_in."&date_out=".$date_out."&page_no=".$total_no_of_pages."'>Último</a></li>";
					}
				echo "<li class=\"next\"><a href=\"#\">Total de registros: <strong>$total_no_of_records</strong></a></li>";
				echo "</ul>
			      </nav>";
		}
	}
}