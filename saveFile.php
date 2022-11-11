<?php

use setasign\Fpdi\Fpdi;

require_once('fpdf181/fpdf.php'); 
require_once('fpdi2/src/autoload.php');

date_default_timezone_set("America/Sao_Paulo");
//Pegando a Data
$date      	=  date("d_m_Y");
//Pegando a hora
$hour        	=  date("H:i:s");
//substituindo : por _ da hora
$str_replace = str_replace(':', '_', $hour);
//número aleatório de 4 dágito
$rand = rand(1000, 9999);

if(isset($_FILES['file-input']) && isset($_POST['name']))
{    
    //Atribuindo variavel para o nome
    $name = $_POST['name'];
    // Verificando se o diretório dos contarios existe, criamos o diretório
    $directory = "Files_Pdf/";
    if (!file_exists($directory)) {
        mkdir($directory, 0777, true);
    }
    $urlFile   = $_FILES["file-input"]["name"]; //Recebendo o arquivo
    
    //Modificando o nome do arquivo
    $new_name_file  = $date.'_'.$str_replace.'_'.$urlFile;

    //nome da assinatura do PDF
    $signature = $name.'_'.$date.'_'.$str_replace.'_'.$rand;

    $signature = str_replace(' ', '_', $signature);

    //Salvando o arquivo na pasta
    $file = $directory . basename($new_name_file); 
    if (move_uploaded_file($_FILES["file-input"] ["tmp_name"], $file)) {
        $pdf = new FPDI();
        $pdf->AddPage(); 
        ///Definir o arquivo PDF que acabou de salvar
        $pdf->setSourceFile('Files_Pdf/'. $new_name_file); 
        //Importa a primeira página do arquivo
        $tpl = $pdf->importPage(1);
        //Usar a página com modelo
        $pdf->useTemplate($tpl); 
        //Definir a fonte da página
        $pdf->SetFont('Arial', 'B', '15');
        //Posição do texto no PDF
        $pdf->SetXY(10,10);
        //$escrevendo no PDF
        $pdf->Write(10, $signature);
        //adicionando uma imagem
        $pdf->Image('assets/img/features-2.png', 90, 248, 40, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        //adicionando pasta para salvar PDF modificados
        $directoryModified = "Files_Pdf_modified/";
        if (!file_exists($directoryModified)) {
            mkdir($directoryModified, 0777, true);
        }
        //Gerando o PDF
        // I: send the file inline to the browser. The PDF viewer is used if available.
        // D: send to the browser and force a file download with the name given by name.
        // F: save to a local file with the name given by name (may include a path).
        // S: return the document as a string.
        $test = $pdf->Output('F','Files_Pdf_modified/'. $signature.'_modified.pdf');  
        //retornando para tela inicial
        header("Location: /index.php");
    }else{
        echo "Erro em salvar arquivo!";
    }
}else{
    ?>
    <script type="text/javascript">
		window.location.href = "index.php";
	</script>
    <?php
}