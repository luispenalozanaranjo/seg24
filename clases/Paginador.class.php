<?php

     class Paginador{
                               
		private $page;
        private $lastPage;
        private $numRegistros;
                              
        public function __construct($pag,$last,$num)
		{
          $this->page=$pag;
          $this->lastPage=$last;
          $this->numRegistros=$num;
        }
                              
          public function muestraPaginador()
		  {
              $salida ="";
                                              
              if($this->numRegistros > 10){
                $salida .= "<table width='100%'>";
                $salida .= "    <tr>";
                                                               
		if ($this->page != 1){
                $salida .= "    <td width='15%'>";
                $salida .= "    <a class='primero' onclick=\"load(1)\"></a>";
                $salida .= "    </td>";
                $prevPage = $this->page-1;
                $salida .= "    <td width='15%'>";
                $salida .= "    <a class='anterior' onclick=\"load($prevPage)\"></a>";
                $salida .= "    </td>";
                }else{
                $salida .= "    <td width='30%'></td>"; 
                }
                $salida .= "    <td style='text-align:center' width='40%'>";
                $salida .= "    <input type='text' style='width:50%' size='2' maxlength='4' id='ir'> ";
                $salida .= "    <a onclick=\"load(document.getElementById('ir').value)\">&nbsp;&nbsp;Ir&nbsp;&nbsp;</a>";
                $salida .= "    </td>";
             
			 if ($this->page != $this->lastPage){
                $nextpage = $this->page+1;
                $salida .= "  <td width='15%'>";
                $salida .= "  <a class='posterior' onclick=\"load($nextpage)\"></a>";
                $salida .= "  </td>";
                $salida .= " <td width='15%'>";
                $salida .= "  <a class='ultimo' onclick=\"load($this->lastPage)\"></a>";
                $salida .= " </td>";
              }else{
                $salida .= "    <td width='30%'></td>";
               }
               $salida .= "    </tr>";
               $salida .= "</table>";
               return $salida;
                  }
                }
                              
       public function cantidadPaginas(){
         return "(".$this->page." de ".$this->lastPage.")";
                               }
                }
?>