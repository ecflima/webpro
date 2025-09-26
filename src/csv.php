<?php

function lercsv($nomearquivo, $verbose = false) {
	$f = fopen($nomearquivo, "r");
	if ($f === false) {
		throw new \Exception('Não foi possível abrir o arquivo');
	}
	$lc = 0;
	$fields_specs = [];	
	$r = [];	
	while (($l = fgets($f)) !== FALSE) {
		$lc += 1;
		//echo "linha$lc: $l\n";
		$eh_comentario = str_starts_with($l, "#");
		if ($eh_comentario) {
			if ($lc === 1) {
				$csv_fields_specs = str_getcsv(substr($l, 1), ";");				
				foreach ($csv_fields_specs as $field_spec) {
					$aux = explode(":", $field_spec);
					$aux_n = count($aux);
					$field_name = $aux[0];
					$field_type = "str";
					if ($aux_n > 1) {
						$field_type = $aux[1];
					}
					$field_constraint = null;
					if ($aux_n > 2) {
						$field_constraint = $aux[2];					
					}
					$fields_specs[$field_name] = [
						"name" => $field_name,
						"type" => $field_type,
						"constraints" => $field_constraint
					];
				}
				$n = count($fields_specs);
				#var_dump($fields_specs);
			}
			continue;
		}
		
		$csvrow = str_getcsv($l, ";");
		//var_dump($csvrow);
		$nn = count($csvrow);
		if ($nn !== $n) {
			throw new \Exception("$nomearquivo:$lc: Era esperado $n campo(s), mas foram encontrado $nn!");
		}
		//echo "linha$lc: $nn campos csv encontrados\n";
		$row = [];
		foreach (array_map(null, array_keys($fields_specs), $csvrow) as [$k, $v]) {			
			$row[$k] = $v;
		}
		foreach ($fields_specs as $k => $spec) {
			$v = $row[$k];
			if ($spec["type"] == "date") {
				$row[$k] = \DateTimeImmutable::createFromFormat("d/m/Y", $v);
			} else if ($spec["type"] == "bool") {
				$row[$k] = filter_var($v, FILTER_VALIDATE_BOOLEAN);
			} else if ($spec["type"] == "array") {
				$row[$k] = explode(":", $v);
			}
		}
		//var_dump($row);
		$r[] = $row;		
		//echo "linha$lc: fim";
	}
    fclose($f);
    return $r;
}