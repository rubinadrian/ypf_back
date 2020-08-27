<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Facturado extends Model
{

    /* Retorna los articulos facturados en el turno (cuerpo) */
    public static function getFacturados($data) {

			return DB::connection('oracle')
			->table('CUERPO')
			->selectRaw('CUERPO.ARTICULO, CUERPO.DENOMINACION, SUM(DECODE(CUERPO.DEBCRE, 1, CUERPO.CANTIDAD, CUERPO.CANTIDAD * -1)) AS CANTIDAD')
			->join('MOVAUDIT', 'MOVAUDIT.COMPINTERNO', '=', 'CUERPO.NROINTERNO')
			->whereRaw('MOVAUDIT.TIPORIG = \'CABPIE\'')
			->whereRaw('CUERPO.BAJA = 0')
			// ->whereRaw('CUERPO.MUEVESTOCK = 0')
			->whereRaw('CUERPO.CANCELACION = 3')
			->whereRaw('upper(MOVAUDIT.USUARIO) LIKE upper(TRIM(\''.$data['usuario'].'\'))')
			->whereRaw("MOVAUDIT.FECHA||LPAD(MOVAUDIT.HORA, 7, '0') BETWEEN ". $data['fhd'] ." AND " . $data['fhh'])
			->groupBy('CUERPO.ARTICULO','CUERPO.DENOMINACION')
			->get();
    }

    /* Retorna los articulos YER facturados en el turno (cuerpo) */
    public static function getFacturadosYER($data, $articulos_yer) {
                return DB::connection('oracle')
            ->table('CUERPO')
            ->selectRaw('PROREMUL.articulohijo as articulo,
              CUERPO.DENOMINACION,
              SUM (DECODE (CUERPO.DEBCRE, 1, CUERPO.CANTIDAD, CUERPO.CANTIDAD * -1) * PROREMUL.cantidad) AS CANTIDAD')
            ->join('MOVAUDIT', 'MOVAUDIT.COMPINTERNO', '=', 'CUERPO.NROINTERNO')
            ->join('PROREMUL','CUERPO.articulo','=','PROREMUL.articulomadre')
            ->whereRaw('MOVAUDIT.TIPORIG = \'CABPIE\'')
            ->whereRaw('CUERPO.BAJA = 0')
            // ->whereRaw('CUERPO.MUEVESTOCK = 0')
            ->whereRaw('CUERPO.CANCELACION = 3')
            ->whereRaw('upper(MOVAUDIT.USUARIO) LIKE upper(TRIM(\''.$data['usuario'].'\'))')
            ->whereRaw("MOVAUDIT.FECHA||LPAD(MOVAUDIT.HORA, 7, '0') BETWEEN ". $data['fhd'] ." AND " . $data['fhh'])
            ->whereIn('articulo', $articulos_yer)
            ->where('PROREMUL.baja', 0)
            ->groupBy('PROREMUL.articulohijo','CUERPO.DENOMINACION')
            ->get();
    }

    public static function getTarjetas() {
      $sql = "SELECT trim(a.clave) as clave,
                     a.valor
                FROM dtabla a
               WHERE (a.Entidad = 'TARJCRED') AND (a.Orden = '02FA')";


      return DB::connection('oracle')->select($sql);
    }

    /* Retorna los comprobantes sin el cuerpo facturados en el turno (cabpie) */
    public static function getComprobantesCaja($data) {

        $sql = "SELECT A.NROINTERNO,
                     A.CANCELACION,
                     A.FECHAORIGEN,
                     hora_c(B.HORA) as HORA,
                     A.TIPOCOMP,
                     A.NROCOMPROBANTE,
                     A.NOMBRE,
                     A.CUIT,
                     A.CIVA,
                     A.TARJCRED,
                     A.IMPTARJ,
                     A.CONTADOCC,
                     A.TOTALCOM,
                     a.nrocaja,
                     a.modulo,
                     a.debcre,
                     a.tipomovim,
                     a.lprecio,
                     d.valor as tarjnombre,
                     (a.TOTALCOM - a.IMPTARJ) as restotarj
                FROM cabpie a
                     LEFT JOIN MOVAUDIT b
                        ON (B.COMPINTERNO = a.nrointerno AND B.TIPORIG = 'CABPIE')
                     LEFT OUTER JOIN dtabla d
                        ON (A.TARJCRED = d.clave
                        AND d.Entidad = 'TARJCRED'
                     AND d.Orden = '02FA')
               WHERE A.BAJA = 0
                     AND A.CANCELACION BETWEEN ".$data['cancelacion_desde']." and ".$data['cancelacion_hasta']."
                     AND B.FECHA||LPAD (B.HORA, 7, '0') BETWEEN ".$data['fhd']." AND " .$data['fhh']."
                     AND UPPER(B.USUARIO) = '". $data['usuario']."'";
        return DB::connection('oracle')->select($sql);
    }

    /** fd y fh son fechas bit */
    public static function getCajasPorFecha($data) {


        $sql = "
              SELECT fechaorigen, nrocaja, B.USUARIO
                FROM cabpie a, movaudit b
               WHERE     A.NROINTERNO = B.COMPINTERNO
                     AND B.TIPORIG = 'CABPIE'
                     AND a.cancelacion > 1
                     AND a.baja = 0
                     AND a.tiponegocio = 2
                     AND a.deposito = 4
                     AND a.empresa = 1
                     AND A.NROCAJA NOT IN (0, 40)
                     AND a.fechaorigen BETWEEN ".$data['fd']." AND " .$data['fh']."
            GROUP BY fechaorigen, nrocaja, B.USUARIO
            ORDER BY fechaorigen, nrocaja";

       return DB::connection('oracle')->select($sql);
    }

}
