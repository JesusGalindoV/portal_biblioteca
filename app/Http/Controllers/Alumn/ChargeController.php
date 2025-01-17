<?php

namespace App\Http\Controllers\Alumn;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Alumns\User;
use App\Models\Alumns\DebitType;
use App\Library\DesicionTree;
use Illuminate\Database\Eloquent\Collection;
use DB;
use Input;
use Auth;

class ChargeController extends Controller
{
	public function index()
	{
        if (!session()->has('chargeTreeInstance')) {
            $tree = new DesicionTree();
            $charge = $tree->makeTree(current_user())->getTreeCharge();
            session(["chargeTreeInstance" => $tree]);
        } else {
            $charge = session()->get('chargeTreeInstance')->getTreeCharge();
        }

        return view('Alumn.charge.index')->with(["instance" => $charge]);
	}


    public function save(Request $request) 
    {
        $alumnRequest = $request->except("_token");
        $tree = session()->get("chargeTreeInstance");
        $charge = $tree->getTreeCharge();

        if (!isset($alumnRequest["seleccionadas"])) {
            session()->flash("messages","error|Hay un minimo de materias por llevar.");
            return redirect()->back();
        }

        foreach ($charge as $key => $value) {
            if (!in_array($value->detGrupoId, $alumnRequest["seleccionadas"])) {
                $value->baja = 1;
            }
        }

        $save = $tree->saveCharge($charge);
        current_user()->closeProccess();

        if ($save) {
            session()->flash("messages","success|Tu carga ha sido guardada.");
        } else {
            session()->flash("messages","warning|Algo salió mal, contacta con servicios escolares.");
        }
        return redirect()->route("alumn.home");
    }

    public function finally(Request $request) {
        current_user()->closeProccess();
        session()->flash("messages","success|El proceso fue finalizado, Revisa tu carga con el personal de servicios escolares.");
        return redirect()->route("alumn.home");
    }
}
