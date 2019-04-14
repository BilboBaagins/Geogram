<?php
if (!empty($_GET['location'])) {
    /**
     *    Notes:
     *      1. YouTube tutorial by WebConcepts on how to mash the Google Geocode API together with the Instagram API.
     *          https://www.youtube.com/watch?v=RTjd1nwvlj4
     *      2. Both APIs have since changed their structures and the example no longer works - so I have adapted 
     *          it to work using the DataScienceToolkit  API (in place of the Google Geocode API) and the Flickr API (in place of the Instagram API)
     *      3. You need to set up a Flickr Account and get an API key - however, you can use the DataScienceToolkit API without the 
     *          need to set up an account.
     */
    $maps_url = 'http://www.datasciencetoolkit.org/maps/api/geocode/json?sensor=false&address=' . urlencode($_GET['location']);
    $maps_json = file_get_contents($maps_url);
    $maps_array = json_decode($maps_json, true);
    $lat = $maps_array['results'][0]['geometry']['location']['lat'];
    $lng = $maps_array['results'][0]['geometry']['location']['lng'];
    /**
     * Time to make our Flickr api request. We'll build the url using the
     * coordinate values returned by the DataScienceToolkit geocode api
     */
    $url = 'https://api.flickr.com/services/rest/?method=flickr.photos.search&api_key=_ENTER_YOUR_FLICKR_API_KEY_HERE_&lat=' . $lat . '&lon=' . $lng . '&format=json&nojsoncallback=1';
        
    //$json = file_get_contents($url);
    //$array = json_decode($json, true);
    
    $data = json_decode(file_get_contents($url));
    
    //$img = 'http://farm'.$farm_id.'.staticflickr.com/'.$server_id.'/'.$photo_id.'_'.$secret_id.'_'.$size.'.'.'jpg';
    
    $photos = $data->photos->photo;
    foreach($photos as $photo){
        $img = 'http://farm'.$photo->farm.'.staticflickr.com/'.$photo->server.'/'.$photo->id.'_'.$photo->secret.'.jpg';
        echo '<img src="'.$img.'">';
        //print_r($photo);
        //echo '<h1>'.$photo->title.'</h1>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>geoflickr</title>
    <script src="script.js"></script>
</head>
<body>
<form action="" method="get">
    <input type="text" name="location"/>
    <button type="submit">Submit</button>
</form>
<br/>
<div id="results" data-url="<?php if (!empty($url)) echo $url ?>">
    <?php
    if (!empty($array)) {
        foreach ($array['data'] as $key => $item) {
            echo '<img id="' . $item['id'] . '" src="' . $item['images']['low_resolution']['url'] . '" alt=""/><br/>';
        }
    }
    ?>
</div>
</body>
</html>
