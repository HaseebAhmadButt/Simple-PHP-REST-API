<?php
$action = $_SERVER["REQUEST_URI"];
$country = explode('/',$action);
$json_data = file_get_contents("Dataset/Assignment Dataset.json");
$updated_country = ucwords($_GET["country"]);
function get_json(){
	global $json_data;
	return json_decode($json_data,true);
}
function add_json($main_array, $json): array
{
	return $main_array[$json["country"]] = $json;
}
if(isset($_GET["sort"])){

    $decoded_jsons = get_json();
    if($_GET["sort"] == "country"){
        usort($decoded_jsons, function ($a, $b){
            return strcmp($a['country'],$b['country']);
        });
        echo json_encode($decoded_jsons);
    }
    elseif ($_GET["sort"] == "team-matches-played"){
        usort($decoded_jsons,function ($a,$b){
            return $a["team-matches-played"] <=> $b["team-matches-played"];
        });
        echo json_encode($decoded_jsons);

    }
    elseif ($_GET["sort"] == "matches-won"){
        usort($decoded_jsons,function ($a,$b){
            return $a["matches-won"] <=> $b["matches-won"];
        });
        echo json_encode($decoded_jsons);
    }
    else{
        usort($decoded_jsons,function ($a,$b){
            return $a["matches-lost"] <=> $b["matches-lost"];
        });
        echo json_encode($decoded_jsons);
    }
}
elseif (isset($_GET["country"]) or isset($_GET["team-matches-played"]) or (isset($_GET["matches-won"])) or (isset($_GET["matches-lost"]))){
        print_r(gettype($_GET["matches-won"]));
        if(isset($_GET["country"])){
            exit("use appropriate query to find data according to Country.");
        }
        $WonMatches = "";
        $LostMatches = "";
        $playedMatches = "";
	$returning_data = array(""=>"");
	//if all values are set
	$decoded_jsons = get_json();

    //Making sure that attribute[condition] does occur along with only attribute name. Because attribute name is fixing the value
    // and at the same time attribute[condition] is setting a range of that value, this conflict should be solved.


	//Picking Only one field and expanding tree to check for set parameters
	//if matches-won is set
	if (isset($_GET["matches-won"])){
//        //if matches-won is array
        if(is_array($_GET["matches-won"])) {
            if (count($_GET["matches-won"]) > 1) {
                exit("Multiple Parameters are not supported. Pass single parameter in array.");
            } else {
                foreach ($_GET["matches-won"] as $key => $wonmatches) {
                    if ($key === "lte") {
                        $WonMatches = "lte";
                    } else {
                        $WonMatches = "gte";
                    }
                    break;
                }
                if (isset($_GET["matches-lost"])) {
                    if (is_array($_GET["matches-lost"])) {
                        if (count($_GET["matches-lost"]) > 1) {
                            exit("Multiple Parameters are not supported. Pass single parameter in array.");
                        } else {
                            foreach ($_GET["matches-lost"] as $key => $lostmatches) {
                                if ($key === "lte") {
                                    $LostMatches = "lte";
                                } else {
                                    $LostMatches = "gte";
                                    break;
                                }
                            }
                            if (isset($_GET["team-matches-played"])) {
                                if (is_array($_GET["team-matches-played"])) {
                                    if (count($_GET["team-matches-played"]) > 1) {
                                        exit("Multiple Parameters are not supported. Pass single parameter in array.");
                                    } else {
                                        foreach ($_GET["team-matches-played"] as $key => $wonmatches) {
                                            if ($key === "lte") {
                                                $playedMatches = "lte";
                                            } else {
                                                $playedMatches = "gte";
                                            }
                                            break;
                                        }
                                        if($WonMatches == "lte" and $LostMatches == "lte"  and $playedMatches == "lte"){
                                            
                                            foreach ($decoded_jsons as $json){
                                                if( ($json["matches-won"]) <=  ($_GET["matches-won"]["lte"])
                                                    and  ($json["matches-lost"]) <=  ($_GET["matches-lost"]["lte"])
                                                    and  ($json["team-matches-played"]) <=  ($_GET["team-matches-played"]["lte"])){
                                                    $returning_data = add_json($returning_data,$json);
                                            }}
                                        }
                                        elseif($WonMatches == "lte" and $LostMatches == "gte"  and $playedMatches == "lte"){
                                            foreach ($decoded_jsons as $json){
                                                if( ($json["matches-won"]) <=  ($_GET["matches-won"]["lte"])
                                                    and  ($json["matches-lost"]) >=  ($_GET["matches-lost"]["gte"])
                                                    and  ($json["team-matches-played"]) <=  ($_GET["team-matches-played"]["lte"])){
                                                    $returning_data = add_json($returning_data,$json);
                                                }}
                                        }
                                        elseif($WonMatches == "gte" and $LostMatches == "lte"  and $playedMatches == "lte"){
                                            foreach ($decoded_jsons as $json){
                                                if( ($json["matches-won"]) >=  ($_GET["matches-won"]["gte"])
                                                    and  ($json["matches-lost"]) <=  ($_GET["matches-lost"]["lte"])
                                                    and  ($json["team-matches-played"]) <=  ($_GET["team-matches-played"]["lte"])){
                                                    $returning_data = add_json($returning_data,$json);
                                                }}
                                        }
                                        elseif($WonMatches == "lte" and $LostMatches == "lte"  and $playedMatches == "gte"){
                                            foreach ($decoded_jsons as $json){
                                                if( ($json["matches-won"]) <=  ($_GET["matches-won"]["lte"])
                                                    and  ($json["matches-lost"]) <=  ($_GET["matches-lost"]["lte"])
                                                    and  ($json["team-matches-played"]) >=  ($_GET["team-matches-played"]["gte"])){
                                                    $returning_data = add_json($returning_data,$json);
                                                }}
                                        }
                                    }
                                }
                                else{
                                    if($WonMatches == "lte" and $LostMatches == "lte"){
                                        foreach ($decoded_jsons as $json){
                                            if( ($json["matches-won"]) <=  ($_GET["matches-won"]["lte"])
                                                and  ($json["matches-lost"]) <=  ($_GET["matches-lost"]["lte"])
                                                and  ($json["team-matches-played"]) ==  ($_GET["team-matches-played"])){
                                                $returning_data = add_json($returning_data,$json);
                                            }}
                                    }
                                    elseif($WonMatches == "gte" and $LostMatches == "lte"){
                                        foreach ($decoded_jsons as $json){
                                            if( ($json["matches-won"]) >=  ($_GET["matches-won"]["gte"])
                                                and  ($json["matches-lost"]) <=  ($_GET["matches-lost"]["lte"])
                                                and  ($json["team-matches-played"]) ==  ($_GET["team-matches-played"])){
                                                $returning_data = add_json($returning_data,$json);
                                            }}
                                    }
                                    elseif($WonMatches == "lte" and $LostMatches == "gte"){
                                        foreach ($decoded_jsons as $json){
                                            if( ($json["matches-won"]) <=  ($_GET["matches-won"]["lte"])
                                                and  ($json["matches-lost"]) >=  ($_GET["matches-lost"]["gte"])
                                                and  ($json["team-matches-played"]) ==  ($_GET["team-matches-played"])){
                                                $returning_data = add_json($returning_data,$json);
                                            }}
                                    }
                                    
                                }
                            }
                            else{
                                if($WonMatches == "lte" and $LostMatches == "lte"){
                                    foreach ($decoded_jsons as $json){
                                        if( ($json["matches-won"]) <=  ($_GET["matches-won"]["lte"])
                                            and  ($json["matches-lost"]) <=  ($_GET["matches-lost"]["lte"])
                                           ){
                                            $returning_data = add_json($returning_data,$json);
                                        }}
                                }
                                elseif($WonMatches == "gte" and $LostMatches == "lte"){
                                    foreach ($decoded_jsons as $json){
                                        if($json["matches-won"] >= $_GET["matches-won"]["gte"]
                                            and $json["matches-lost"] <= $_GET["matches-lost"]["lte"])
                                        {
                                            $returning_data = add_json($returning_data,$json);
                                        }}
                                }
                                elseif($WonMatches == "lte" and $LostMatches == "gte"){
                                    foreach ($decoded_jsons as $json){
                                        if( ($json["matches-won"]) <=  ($_GET["matches-won"]["lte"])
                                            and  ($json["matches-lost"]) >=  ($_GET["matches-lost"]["gte"])
                                        ){
                                            $returning_data = add_json($returning_data,$json);
                                        }}
                                }
                            }
                        }
                    }
                    else{
                        if($WonMatches == "lte") {
                            foreach ($decoded_jsons as $json) {
                                if ( ($json["matches-won"]) <=  ($_GET["matches-won"]["lte"]) and
                                     ($json["matches-lost"]) ==  ($_GET["matches-lost"])) {
                                    $returning_data = add_json($returning_data, $json);
                                }
                            }
                        }
                        else{
                            foreach ($decoded_jsons as $json) {
                                if ( ($json["matches-won"]) >=  ($_GET["matches-won"]["gte"]) and
                                     ($json["matches-lost"]) ==  ($_GET["matches-lost"])) {
                                    $returning_data = add_json($returning_data, $json);
                                }
                            }
                        }
                    }
                }
                else{
                    if($WonMatches == "lte"){
                        foreach ($decoded_jsons as $json) {
                            if ( ($json["matches-won"]) <=  ($_GET["matches-won"]["lte"])) {
                                $returning_data = add_json($returning_data, $json);
                            }
                        }
                    }
                    else{
                        foreach ($decoded_jsons as $json) {
                            if ( ($json["matches-won"]) >=  ($_GET["matches-won"]["gte"])) {
                                $returning_data = add_json($returning_data, $json);
                            }
                        }
                    }
                }
            }
        }
        else{

            if (isset($_GET["matches-lost"])) {
                if (is_array($_GET["matches-lost"])) {
                    if (count($_GET["matches-lost"]) > 1) {
                        exit("Multiple Parameters are not supported. Pass single parameter in array.");
                    } else {
                        foreach ($_GET["matches-lost"] as $key => $lostmatches) {
                            if ($key === "lte") {
                                $LostMatches = "lte";
                            } else {
                                $LostMatches = "gte";
                                break;
                            }
                        }
                        if (isset($_GET["team-matches-played"])) {
                            if (is_array($_GET["team-matches-played"])) {
                                if (count($_GET["team-matches-played"]) > 1) {
                                    exit("Multiple Parameters are not supported. Pass single parameter in array.");
                                } else {
                                    foreach ($_GET["team-matches-played"] as $key => $wonmatches) {
                                        if ($key === "lte") {
                                            $playedMatches = "lte";
                                        } else {
                                            $playedMatches = "gte";
                                        }
                                        break;
                                    }
                                    if($LostMatches == "lte"  and $playedMatches == "lte"){

                                        foreach ($decoded_jsons as $json){
                                            if( ($json["matches-won"]) ==  ($_GET["matches-won"])
                                                and  ($json["matches-lost"]) <=  ($_GET["matches-lost"]["lte"])
                                                and  ($json["team-matches-played"]) <=  ($_GET["team-matches-played"]["lte"])){
                                                $returning_data = add_json($returning_data,$json);
                                            }}
                                    }
                                    elseif($LostMatches == "gte"  and $playedMatches == "lte"){
                                        foreach ($decoded_jsons as $json){
                                            if( ($json["matches-won"]) ==  ($_GET["matches-won"])
                                                and  ($json["matches-lost"]) >=  ($_GET["matches-lost"]["gte"])
                                                and  ($json["team-matches-played"]) <=  ($_GET["team-matches-played"]["lte"])){
                                                $returning_data = add_json($returning_data,$json);
                                            }}
                                    }
                                    elseif($LostMatches == "lte"  and $playedMatches == "gte"){
                                        foreach ($decoded_jsons as $json){
                                            if( ($json["matches-won"]) ==  ($_GET["matches-won"])
                                                and  ($json["matches-lost"]) <=  ($_GET["matches-lost"]["gte"])
                                                and  ($json["team-matches-played"]) >=  ($_GET["team-matches-played"]["lte"])){
                                                $returning_data = add_json($returning_data,$json);
                                            }}
                                    }
                                }
                            }
                            else{
                                if($LostMatches == "lte"){
                                    foreach ($decoded_jsons as $json){
                                        if( ($json["matches-won"]) ==  ($_GET["matches-won"])
                                            and  ($json["matches-lost"]) <=  ($_GET["matches-lost"]["lte"])
                                            and  ($json["team-matches-played"]) ==  ($_GET["team-matches-played"])){
                                            $returning_data = add_json($returning_data,$json);
                                        }}
                                }
                                elseif($LostMatches == "gte"){
                                    foreach ($decoded_jsons as $json){
                                        if( ($json["matches-won"]) ==  ($_GET["matches-won"])
                                            and  ($json["matches-lost"]) >=  ($_GET["matches-lost"]["gte"])
                                            and  ($json["team-matches-played"]) ==  ($_GET["team-matches-played"])){
                                            $returning_data = add_json($returning_data,$json);
                                        }}
                                }
                            }
                        }
                        else{
                            if($LostMatches == "lte"){
                                foreach ($decoded_jsons as $json){
                                    if( ($json["matches-won"]) ==  ($_GET["matches-won"])
                                        and  ($json["matches-lost"]) <=  ($_GET["matches-lost"]["lte"])
                                    ){
                                        $returning_data = add_json($returning_data,$json);
                                    }}
                            }
                            elseif($LostMatches == "gte"){
                                foreach ($decoded_jsons as $json){
                                    if( ($json["matches-won"]) ==  ($_GET["matches-won"])
                                        and  ($json["matches-lost"]) >=  ($_GET["matches-lost"]["gte"])
                                    ){
                                        $returning_data = add_json($returning_data,$json);
                                    }}
                            }
                        }
                    }
                }
                else{
                    foreach ($decoded_jsons as $json) {
                        if ( ($json["matches-won"]) ==  ($_GET["matches-won"]) and
                             ($json["matches-lost"]) ==  ($_GET["matches-lost"])) {
                            $returning_data = add_json($returning_data, $json);
                        }
                    }
                }
            }
            elseif(isset($_GET["team-matches-played"])){
                    if (is_array($_GET["team-matches-played"])) {
                        if (count($_GET["team-matches-played"]) > 1) {
                            exit("Multiple Parameters are not supported. Pass single parameter in array.");
                        } else {
                            foreach ($_GET["team-matches-played"] as $key => $wonmatches) {
                                if ($key === "lte") {
                                    $playedMatches = "lte";
                                } else {
                                    $playedMatches = "gte";
                                }
                                break;
                            }
                            if($playedMatches == "lte"){

                                foreach ($decoded_jsons as $json){
                                    if( ($json["matches-won"]) ==  ($_GET["matches-won"])
                                        and  ($json["team-matches-played"]) <=  ($_GET["team-matches-played"]["lte"])){
                                        $returning_data = add_json($returning_data,$json);
                                    }}
                            }
                            elseif($playedMatches == "gte"){
                                foreach ($decoded_jsons as $json){
                                    if( ($json["matches-won"]) ==  ($_GET["matches-won"])
                                        and  ($json["team-matches-played"]) >=  ($_GET["team-matches-played"]["gte"])){
                                        $returning_data = add_json($returning_data,$json);
                                    }}
                            }
                        }
                    }
                    else{
                        foreach ($decoded_jsons as $json){
                            if( ($json["matches-won"]) ==  ($_GET["matches-won"])
                                and  ($json["team-matches-played"]) ==  ($_GET["team-matches-played"])){
                                $returning_data = add_json($returning_data,$json);
                            }}
                    }

            }
            else{
                foreach ($decoded_jsons as $json) {
                    if ( ($json["matches-won"]) ==  ($_GET["matches-won"])) {
                        $returning_data = add_json($returning_data, $json);
                    }
                }
            }
        }
    }
    elseif ($_GET["matches-lost"]){
            if (is_array($_GET["matches-lost"])) {
                if (count($_GET["matches-lost"]) > 1) {
                    exit("Multiple Parameters are not supported. Pass single parameter in array.");
                } else {
                    foreach ($_GET["matches-lost"] as $key => $lostmatches) {
                        if ($key === "lte") {
                            $LostMatches = "lte";
                        } else {
                            $LostMatches = "gte";
                            break;
                        }
                    }
                    if (isset($_GET["team-matches-played"])) {
                        if (is_array($_GET["team-matches-played"])) {
                            if (count($_GET["team-matches-played"]) > 1) {
                                exit("Multiple Parameters are not supported. Pass single parameter in array.");
                            } else {
                                foreach ($_GET["team-matches-played"] as $key => $wonmatches) {
                                    if ($key === "lte") {
                                        $playedMatches = "lte";
                                    } else {
                                        $playedMatches = "gte";
                                    }
                                    break;
                                }
                                if($LostMatches == "lte"  and $playedMatches == "lte"){

                                    foreach ($decoded_jsons as $json){
                                        if( ($json["matches-lost"]) <=  ($_GET["matches-lost"]["lte"])
                                            and  ($json["team-matches-played"]) <=  ($_GET["team-matches-played"]["gte"])){
                                            $returning_data = add_json($returning_data,$json);
                                        }}
                                }
                                elseif($LostMatches == "gte"  and $playedMatches == "lte"){
                                    foreach ($decoded_jsons as $json){
                                        if( ($json["matches-lost"]) >=  ($_GET["matches-lost"]["gte"])
                                            and  ($json["team-matches-played"]) <=  ($_GET["team-matches-played"]["lte"])){
                                            $returning_data = add_json($returning_data,$json);
                                        }}
                                }
                                elseif($LostMatches == "lte"  and $playedMatches == "gte"){
                                    foreach ($decoded_jsons as $json){
                                        if( ($json["matches-lost"]) <=  ($_GET["matches-lost"]["lte"])
                                            and  ($json["team-matches-played"]) >=  ($_GET["team-matches-played"]["lte"])){
                                            $returning_data = add_json($returning_data,$json);
                                        }}
                                }

                            }
                        }
                        else{
                            if($LostMatches == "lte"){
                                foreach ($decoded_jsons as $json){
                                    if( ($json["matches-lost"]) <=  ($_GET["matches-lost"]["lte"])
                                        and  ($json["team-matches-played"]) ==  ($_GET["team-matches-played"])){
                                        $returning_data = add_json($returning_data,$json);
                                    }}
                            }
                            elseif($LostMatches == "gte"){
                                foreach ($decoded_jsons as $json){
                                    if( ($json["matches-lost"]) >=  ($_GET["matches-lost"]["gte"])
                                        and  ($json["team-matches-played"]) ==  ($_GET["team-matches-played"])){
                                        $returning_data = add_json($returning_data,$json);
                                    }}
                            }
                        }
                    }
                    else{
                        if($LostMatches == "lte"){
                            foreach ($decoded_jsons as $json){
                                if( ($json["matches-lost"]) <=  ($_GET["matches-lost"]["lte"])
                                ){
                                    $returning_data = add_json($returning_data,$json);
                                }}
                        }
                        elseif($LostMatches == "gte"){
                            foreach ($decoded_jsons as $json){
                                if( ($json["matches-lost"]) >=  ($_GET["matches-lost"]["gte"])
                                ){
                                    $returning_data = add_json($returning_data,$json);
                                }}
                        }
                    }
                }
            }
            else{
                foreach ($decoded_jsons as $json) {
                    if ( ($json["matches-lost"]) ==  ($_GET["matches-lost"])) {
                        $returning_data = add_json($returning_data, $json);
                    }
                }
            }
    }
	//if matches-won is not set
	else{
        if (isset($_GET["team-matches-played"])) {
            if (is_array($_GET["team-matches-played"])) {
                if (count($_GET["team-matches-played"]) > 1) {
                    exit("Multiple Parameters are not supported. Pass single parameter in array.");
                } else {
                    foreach ($_GET["team-matches-played"] as $key => $wonmatches) {
                        if ($key === "lte") {
                            $playedMatches = "lte";
                        } else {
                            $playedMatches = "gte";
                        }
                        break;
                    }
                    if($playedMatches == "lte"){
                        foreach ($decoded_jsons as $json){
                            if( ($json["team-matches-played"]) <=  ($_GET["team-matches-played"]["lte"])){
                                $returning_data = add_json($returning_data,$json);
                            }}
                    }
                    else{
                        foreach ($decoded_jsons as $json){
                        if( ($json["team-matches-played"]) >=  ($_GET["team-matches-played"]["gte"])){
                            $returning_data = add_json($returning_data,$json);
                        }}
                    }
                }
            }
            else{
                foreach ($decoded_jsons as $json){
                    if( ($json["team-matches-played"]) ==  ($_GET["team-matches-played"])){
                        $returning_data = add_json($returning_data,$json);
                    }}
            }
        }

	}

	echo json_encode($returning_data);
}
else
{
	if ($action == "/") {
        echo "<h2>1. Queries Examples<h2>";
        echo "<h4>2. To Get Data About Specific Country use Query: /data/{Country Name}</h4>";
        echo "<h4>3. To Get All Data Uue Query: /data or /data/</h4>";
        echo "<h4>4. To Get Sorted Data: /data/sor={Field Name}</h4>";
        echo "<h5 style='color: red'>Note, In single Query use only one Field/Attribute to sort data multiple attributes in single query is not supported</h5>";
        echo "<h4>5. To Get Data according to set of attributes use Query: /data?{Attribute Name}[lte OR gte: this is optional]";
        echo "<h5 style='color: red'>OR</h5>";
        echo "<h4>6. /data?{Attribute Name}[lte OR gte: this is optional]&{Attribute Name}[lte OR gte: this is optional]....</h4>";
        echo "<h5 style='color: red'>Note: Data According to Country Can't Be Fetched According to this Rule. Use Query 1 To Get Specific Data According to Country</h5>";


    }
    elseif ($action == "/data" or $action == "/data/") {
		echo json_encode($json_data);
	}
    elseif (count($country) == 3) {
		$params = [];
		$Country = $country[2];
		$Country = ucwords(str_replace("%20", " ", $Country));
		$decoded_jsons = json_decode($json_data, true);
		foreach ($decoded_jsons as $json) {
			if ($Country == $json["country"]) {
				$params = $json;
				break;
			}
		}
		echo json_encode($params);
	} else {
		echo json_encode(null);
	}
}

