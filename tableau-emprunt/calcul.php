<?php
$periodicite = $_POST['periodicite'];
$montant = $_POST['montant'];
$type = $_POST['type'];
$montants[1]= $montant;
$tauxInteretAnnuel = $_POST['tauxInteretAnnuel']/100;
$nbAnnee = $_POST['nbAnnee'];
$tauxInteret = $tauxInteretAnnuel;
if ($periodicite == 'mensuel'){
    $nbAnnee = $_POST['nbAnnee'] * 12;
    $tauxInteret = (1+$tauxInteretAnnuel)**(1/12) - 1;
}
if ($periodicite == 'semestriel'){
    $nbAnnee = $nbAnnee * 2;
    $tauxInteret = (1+$tauxInteretAnnuel)**(1/2) - 1;
}
if ($periodicite == 'trimestriel'){
    $nbAnnee = $nbAnnee * 4;
    $tauxInteret = (1+$tauxInteretAnnuel)**(1/4) - 1;
}


$html = <<<HTML
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>
<table class="table table-striped">
<thead class="thead-dark">
<tr>
    <th>Période</th>
    <th>Capital restant en début de période</th>
    <th>Intérêts de la période</th>
    <th>Amortissement du capital</th>
    <th>Annuité d'emprunt</th>
    <th>Capital restant dû en fin de mois</th>
</tr>
</thead>
    
HTML;

if ($type == 'Annuite') {
    $constante = round($montant * ($tauxInteret / (1 - (1+$tauxInteret)**-5)),2);
    if ($periodicite == 'mensuel'){
        $constante = round($montant * $tauxInteret / (1-(1+$tauxInteret)**-$nbAnnee), 2);
    }
    if ($periodicite == 'semestriel'){
        $constante = round($montant * $tauxInteret / (1-(1+$tauxInteret)**-$nbAnnee), 2);
    }
    if($periodicite == 'trimestriel'){
        $constante = round($montant * $tauxInteret / (1-(1+$tauxInteret)**-$nbAnnee), 2);
    }
    for($i=1;$i<=$nbAnnee;$i++){
        $Interet[$i] = round($montants[$i] * $tauxInteret,2);
        $amortissements[$i] = round($constante - $Interet[$i],2);
        $capitalrest[$i] = round($montants[$i] - $amortissements[$i],2);
        if ($i < $nbAnnee)
        {
            $montants[$i+1] = $capitalrest[$i];
        }
        if($i == $nbAnnee){
            $capitalrest[$i] = 0;
        }

        $html.= <<<HTML
            <tr>
                <td>$i</td>
                <td>$montants[$i]</td>
                <td>$Interet[$i]</td>
                <td>$amortissements[$i]</td>
                <td>$constante</td>
                <td>$capitalrest[$i]</td>
            </tr>
HTML;

    }
    $totalinteret = round(array_sum($Interet),2);
    $totalamort = round(array_sum($amortissements),2);
    $totalannui = round($constante* $nbAnnee,2);
    $html.= <<<HTML
    <tr class="table-success">
        <td>
        Total
        </td>
        <td>////////////////////////</td>
        <td>$totalinteret</td>
        <td>$totalamort</td>
        <td>$totalannui</td>
        <td>////////////////////////</td>
    </tr>
HTML;

}
else{
    $constante = round($montant / $nbAnnee ,2);
    for($i=1;$i<=$nbAnnee;$i++){
        $Interet[$i] = round($montants[$i] * $tauxInteret,2);
        $annuité[$i] = round($constante + $Interet[$i],2);
        $capitalrest[$i] = round($montants[$i] - $constante,2);
        if ($i < $nbAnnee)
        {
            $montants[$i+1] = $capitalrest[$i];
        }
        if($i == $nbAnnee){
            $capitalrest[$i] = 0;
        }

        $html.= <<<HTML
            <tr>
                <td>$i</td>
                <td>$montants[$i]</td>
                <td>$Interet[$i]</td>
                <td>$constante</td>
                <td>$annuité[$i]</td>
                <td>$capitalrest[$i]</td>
            </tr>
HTML;

    }
    $totalinteret = round(array_sum($Interet),2);
    $totalamort = round(array_sum($annuité),2);
    $totalannui = round($constante* $nbAnnee,2);
    $html.= <<<HTML
    <tr class="table-success">
        <td>
        Total
        </td>
        <td>////////////////////////</td>
        <td>$totalinteret</td>
        <td>$totalannui</td>
        <td>$totalamort</td>
        <td>////////////////////////</td>
    </tr>
HTML;
}

$html .= '</table>';

echo $html;