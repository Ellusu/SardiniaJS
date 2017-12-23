<?php
    $file = file_get_contents('poi.csv');
    $righe = explode(chr(10),$file);
    
    $ico = array(
        'Città e paesi'                 =>  'town',
        'Porti'                         =>  'harbor',
        'Edifici e strutture antiche'   =>  'castle',
        'Spiagge'                       =>  'swimming',
        'Aree marine protette'          =>  'ferry',
        'Insediamenti antichi'          =>  'town-hall',
        'Chiese'                        =>  'monument',
        'Isole'                         =>  'embassy',
        'Grotte mare'                   =>  'information',
        'Museo'                         =>  'museum',
        'Spazi sacri e funerari'        =>  'landmark',
        'Parchi'                        =>  'parking',
        'Foreste'                       =>  'natural',
        'Monti'                         =>  'mountain',
        'Monumenti megalitici'          =>  'place-of-worship',
        'Aree protette'                 =>  'heliport',
        'Monumenti naturali'            =>  'park',
        'SentieriSorgenti e cascate'    =>  'park-alt1',
        'Grotte montagna'               =>  'aerialway',
        'Laghi'                         =>  'picnic-site',//
        'orto'                          =>  'florist'
        );
    
    $var = array(
        'lng'       => FALSE,            
        'tipe'      => FALSE,
        'search'    => FALSE
    );
    
    if(isset($_GET["lang"])) {
        $var['lang'] = $_GET['lang'];
    } else {
        $var['lang'] = 'it';        
    }
    if(isset($_GET["tipe"])) {
        $var['tipe'] = $_GET['tipe'];        
    } else {
        $var['tipe'] = FALSE;        
    }
    if(isset($_GET["search"])) {
        $var['search'] = $_GET['search'];        
    } else {
        $var['search'] = FALSE;        
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8' />
    <?php if($var["tipe"]) {?>    
        <title><?php echo ucfirst($var["search"]); ?> - SardiniaJS</title>
    <?php } else {?> 
        <title>SardiniaJS</title>
    <?php } ?> 
    <meta name="viewport" content="width=device-width" />
    <meta name='viewport' content='initial-scale=1,maximum-scale=1,user-scalable=no' />
    <script src="https://matteoenna.it/geo/reactgeo/sardegnaopenreact/lib/js/jquery-3.2.1.min.js"></script>

    <script src='https://api.tiles.mapbox.com/mapbox-gl-js/v0.43.0/mapbox-gl.js'></script>
    <link href='https://api.tiles.mapbox.com/mapbox-gl-js/v0.43.0/mapbox-gl.css' rel='stylesheet' />
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <style>
        body { margin:0; padding:0; }
        #map { position:absolute; top:50px; bottom:50px; width:100%; }
        #getter { position:absolute; height: 50px; bottom:0px; width:100%; }
        #header { position:absolute; height: 50px; top:0px; width:100%; }
        h1,h2 { margin-top: 0; }
        h2 { text-align: right; }
        .lang a {text-align: center; padding: 0; margin-top: 10px;}
    </style>
</head>
<body>
<style>
    .mapboxgl-popup {
        max-width: 400px;
        font: 12px/20px 'Helvetica Neue', Arial, Helvetica, sans-serif;
    }
    
    #menu {
        position: fixed;
        z-index: 999999;
        top:0;
    }
    
    .sidenav {
        height: 100%;
        width: 0;
        position: fixed;
        z-index: 3;
        top: 0;
        right: 0;
        background-color: #111;
        overflow-x: hidden;
        transition: 0.5s;
        padding-top: 60px;
    }
    
    .sidenav a {
        padding: 8px 8px 8px 32px;
        text-decoration: none;
        font-size: 17px;
        color: #818181;
        display: block;
        transition: 0.3s;
    }
    
    .sidenav a:hover {
        color: #f1f1f1;
    }
    
    .sidenav .closebtn {
        position: absolute;
        top: 0;
        right: 25px;
        font-size: 36px;
        margin-left: 50px;
    }
    
    @media screen and (max-width: 800px) {
        #map { position:absolute; top:100px; bottom:50px; width:100%; }
          h1,h2 {
            text-align: center;
        }
    }
    
    @media screen and (max-width: 450px) {
        #header h2 {display: none; visibility: hidden;}
    }
    
    @media screen and (max-height: 450px) {
      .sidenav {padding-top: 15px;}
      .sidenav a {font-size: 18px;}
    }
</style>
<span style="font-size:30px;cursor:pointer" onclick="openNav()" id="menu" class="only-mobile">&#9776;</span>
<div id="header">
    <div class="container">   
        <h1 class="col-md-4">
    <?php if($var["tipe"]) {?> <?php echo ucfirst($var["search"]); ?> - SardiniaJS
    <?php } else {?> 
        SardiniaJS
    <?php } ?>
        </h1>
        <h2 class="col-md-8">
            <?php
            switch ($var['lang']) {
                case 'en':
                    echo "Sardinia: History, Art and Nature";
                    break;
                case 'es':
                    echo "Cerdeña: historia, arte y naturaleza";
                    break;
                case 'fr':
                    echo "Sardaigne: Histoire, Art et Nature";
                    break;
                case 'it':
                    echo "Sardegna: Storia, Arte e Natura";
                    break;
            }
            ?>
        </h2>
    </div>
</div>
<div id='map'></div>
<div id="mySidenav" class="sidenav only-mobile">
  <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
    <?php
    $ar = array();
    foreach ($righe as $k=>$riga){
        $col = explode(';',$riga);
        $ar[] = $col[0];
    }
    $result = array_unique($ar);
    sort($result);
    foreach ($result as $comune){ ?>
        <a href="https://matteoenna.it/geo/js/sardiniaJs/<?php echo $var['lang']?>/town/<?php echo strtolower($comune); ?>.html"><?php echo $comune; ?></a>
    <?php } ?>
</div>
<div id='getter'>
    <div class="container">
        <div  class="col-md-2 lang">
            <a href="https://matteoenna.it/geo/js/sardiniaJs/it.html" class="col-md-3 col-xs-3">it</a>
            <a href="https://matteoenna.it/geo/js/sardiniaJs/en.html" class="col-md-3 col-xs-3">en</a>
            <a href="https://matteoenna.it/geo/js/sardiniaJs/fr.html" class="col-md-3 col-xs-3">fr</a>
            <a href="https://matteoenna.it/geo/js/sardiniaJs/es.html" class="col-md-3 col-xs-3">es</a>
        </div>
        <div  class="col-md-10">
            <?php
            $frase = '';
            switch ($var['lang']) {
                case 'en':
                    $frase =  "Made by Matteo Enna, Web developer and Open Source Evangelist. Only Free Software was used in the project!";
                    break;
                case 'es':
                    $frase =  "Hecho por Matteo Enna, desarrollador web y Open Source Evangelist. ¡Solo se usó software libre en el proyecto!";
                    break;
                case 'fr':
                    $frase =  "Fabriqué par Matteo Enna, développeur Web et Open Source Evangelist. Seul le logiciel libre a été utilisé dans le projet!";
                    break;
                case 'it':
                    $frase =  "Realizzato da Matteo Enna, sviluppatore Web ed Open Source Evangelist. Nel progetto è stato utilizzato solo Software Libero!";
                    break;
            }
            
            echo '<p style="margin-top:10px">'.str_replace('Matteo Enna','<a href="https://matteoenna.it">Matteo Enna</a>',$frase).'</p>';
            ?>
            
        </div>
    </div>
</div>
<script>
mapboxgl.accessToken = '';

var map = new mapboxgl.Map({
    container: 'map',
    style: 'mapbox://styles/mapbox/streets-v9',
<?php if($var['tipe']=='town'){
    foreach ($righe as $k=>$riga){
        $col = explode(';',$riga);
        if($var['search']==strtolower($col[0]) && $col['4']=='Città e paesi'){
            $x = $col[8];
            $y = $col[9];
        }
    }
    
?>
    center: [<?php echo $y?>, <?php echo $x?>],
    zoom: 13
<?php }else{?>
    center: [8.82, 40.12],
    zoom: 7    
<?php } ?>
});

map.on('load', function () {
    // Add a layer showing the places.
    map.addLayer({
        "id": "places",
        "type": "symbol",
        "source": {
            "type": "geojson",
            "data": {
                "type": "FeatureCollection",
                "features": [
                    <?php
                        unset($righe[0]);
                        foreach ($righe as $k=>$riga){
                            $col = explode(';',$riga);
                            if($var['tipe']) {
                                if($var['tipe']=='cat') {
                                    if($var['search']!=strtolower($col[5])) continue;                                    
                                }
                                if($var['tipe']=='town') {
                                    if($var['search']!=strtolower($col[0])) continue;
                                }
                            }
                            $ru = '<p><a href=\"https://matteoenna.it/geo/js/sardiniaJs/'.$var["lang"].'/town/'.strtolower($col[0]).'.html\" title=\"Opens in a new window\">'.$col[0].'</a> - ';
                            $ru .= '<a href=\"https://matteoenna.it/geo/js/sardiniaJs/'.$var["lang"].'/cat/'.strtolower($col[5]).'.html\" title=\"Opens in a new window\">'.$col[5].'</a></p>';
                            $ru .= '<a href=\"https://matteoenna.it/sardegnaopenbootstrap/'.strtolower($col[0]).'.html\" target=\"_blank\" title=\"Opens in a new window\">SardegnaOpenBootstrap</a></p>';
                    ?>
                             {
                    "type": "Feature",
                    "properties": {
                        "description": "<strong><?php echo $col[3]?></strong><?php echo $ru; ?><?php echo $rd; ?>",
                        "icon": "<?php echo $ico[$col[4]]; ?>"
                    },
                    "geometry": {
                        "type": "Point",
                        "coordinates": [<?php echo $col[9]?>, <?php echo $col[8]?>]
                    }
                },
                <?php } ?>
                ]
            }
        },
        "layout": {
            "icon-image": "{icon}-15",
            "icon-allow-overlap": true
        }
    });

    // When a click event occurs on a feature in the places layer, open a popup at the
    // location of the feature, with description HTML from its properties.
    map.on('click', 'places', function (e) {
        new mapboxgl.Popup()
            .setLngLat(e.features[0].geometry.coordinates)
            .setHTML(e.features[0].properties.description)
            .addTo(map);
    });

    // Change the cursor to a pointer when the mouse is over the places layer.
    map.on('mouseenter', 'places', function () {
        map.getCanvas().style.cursor = 'pointer';
    });

    // Change it back to a pointer when it leaves.
    map.on('mouseleave', 'places', function () {
        map.getCanvas().style.cursor = '';
    });
});
</script>

<script>
function openNav() {
    document.getElementById("mySidenav").style.width = "250px";
}

function closeNav() {
    document.getElementById("mySidenav").style.width = "0";
}
</script>
</body>
</html>
