<?php

namespace App\Http\Controllers;

use App\Models\Livro;
use Illuminate\Http\Request;

class LivroController extends Controller
{
    public function get(int $pagina = null)
    {
        try {
            $livros = Livro::select();
            if (!is_null($pagina) && $pagina != 0) {
                $valor = $pagina*20;
                $livros->offset($valor);
            }
            $livros =  $livros->limit(20)->get();
            return response()->json($livros, 200);
        } catch (\Exception $th) {
            return response()->json($th->getMessage(), 500);
        }
    }

    public function buscar(Request $request) 
    {
        try {
            if (empty($request->busca)) {
                return $this->get();
            }
            $livros = Livro::where('nome', 'like', '%' . $request->busca .'%')
                ->orWhere('autor', 'like', '%' . $request->busca .'%')
                ->orWhere('editora', 'like', '%' . $request->busca . '%')
                ->get();
            $botoes = $livros->count() ? false : true;
            return response()->json(['livros' => $livros, 'botoes' => $botoes], 200);

        } catch (\Throwable $th) {
            return response()->json([], 500);
        }
    }
}
