<?php
namespace App\Controller;
use App\Form\ContactoType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Musica;
use App\Entity\Autor;

class MusicaController extends AbstractController{

    private $musicas = [
        5 => ["nombre" => "disco9", "precio" => 100, "autor_id" => 1],
        6 => ["nombre" => "disco7", "precio" => 60, "autor_id" => 1],
        7 => ["nombre" => "disco4", "precio" => 30, "autor_id" => 1],
        8 => ["nombre" => "disco3", "precio" => 24, "autor_id" => 1],
        9 => ["nombre" => "disco8", "precio" => 89, "autor_id" => 1]
    ];  
    
    //Insertar nueva musica mediante formulario
    #[Route('/musica/nuevo', name: 'nueva_musica')]
    public function nuevo(ManagerRegistry $doctrine, Request $request) {
        $contacto = new Musica();
        $formulario = $this->createForm(ContactoType::class, $contacto);
        $formulario->handleRequest($request);
        if ($formulario->isSubmitted() && $formulario->isValid()) {
            $contacto = $formulario->getData();
            $entityManager = $doctrine->getManager();
            $entityManager->persist($contacto);
            $entityManager->flush();
            return $this->redirectToRoute('ficha_contacto', ["codigo" => $contacto->getId()]);
        }
        return $this->render('nuevo.html.twig', array(
            'formulario' => $formulario->createView()
        ));
    }

    //Mostrar todas las musicas
    #[Route('/musica/mostrar/todo', name: 'mostrar_todo_user')]
    public function mostrarTodo(){

    }    

    //Editar una musica, abriendo un formulario segun la ID de la musica elegida 
    #[Route('/musica/editar/{codigo}', name: 'editar_musica', requirements:["codigo"=>"\d+"])]
    public function editar(ManagerRegistry $doctrine, Request $request, $codigo) {
        $repositorio = $doctrine->getRepository(Musica::class);

        $musica = $repositorio->find($codigo);
        if ($musica){
            $formulario = $this->createForm(ContactoType::class, $musica);
            $formulario->handleRequest($request);

            if ($formulario->isSubmitted() && $formulario->isValid()) {
                $musica = $formulario->getData();
                $entityManager = $doctrine->getManager();
                $entityManager->persist($musica);
                $entityManager->flush();
                return $this->redirectToRoute('ficha_musica', ["codigo" => $musica->getId()]);
            }
            return $this->render('nuevo.html.twig', array(
                'formulario' => $formulario->createView()
            ));
        }else{
            return $this->render('ficha_musica.html.twig', [
                'musica' => NULL
            ]);
        }
    }
    
    //Insertar nueva musica, segun el array de arriba, con autor Prueba: http://127.0.0.1:8080/musica/insertar
    #[Route('/musica/insertar', name: 'insertar_musica')]
    public function insertar(ManagerRegistry $doctrine)
    {
        $entityManager = $doctrine->getManager();
        $repositorio = $doctrine->getRepository(Autor::class);
        foreach($this->musicas as $c){
            $musica = new Musica();
            $musica->setNombre($c["nombre"]);
            $musica->setPrecio($c["precio"]);
            $autor = $repositorio->find($c["autor_id"]);
            $musica->setAutor($autor);
            $entityManager->persist($musica);
        }
        try{
            $entityManager->flush();
            return new Response("Musicas insertadas");
        } catch (\Exception $e) {
            return new Response("Error insertando objetos");
        }  
    }
    
    //Buscar musica segun texto, muestra varias musicas Prueba: http://127.0.0.1:8080/musica/buscar/disco
    #[Route('/musica/buscar/{texto}', name: 'buscar_musica')]
    public function buscar(ManagerRegistry $doctrine, $texto): Response{
        //Filtramos aquellos que contengan dicho texto en el nombre
        $repositorio = $doctrine->getRepository(Musica::class);
    
        $musicas = $repositorio->findByName($texto);
    
        return $this->render('search/lista_musicas.html.twig', [
            'musicas' => $musicas
        ]);        
    }

    //Buscar musica segun la ID Prueba: http://127.0.0.1:8080/musica/2
    #[Route('/musica/{codigo}', name: 'ficha_musica')]
    public function ficha(ManagerRegistry $doctrine, $codigo): Response{
	    $repositorio = $doctrine->getRepository(Musica::class);
	    $musica = $repositorio->find($codigo);

	    return $this->render('search/ficha_musica.html.twig', [
	    	'musica' => $musica
	    ]);
	}    
    
    //Modificar el nombre de la musica segun la ID Prueba: http://127.0.0.1:8080/musica/update/1/disco3
    #[Route('/musica/update/{id}/{nombre}', name: 'modificar_musica')]
    public function update(ManagerRegistry $doctrine, $id, $nombre): Response{
        $entityManager = $doctrine->getManager();
        $repositorio = $doctrine->getRepository(Musica::class);
        $musica = $repositorio->find($id);
        if ($musica){
            $musica->setNombre($nombre);
            try{
                $entityManager->flush();
                return $this->render('search/ficha_musica.html.twig', [
                    'musica' => $musica
                ]);
            } catch (\Exception $e) {
                return new Response("Error insertando objetos" . $e->getMessage());
            }  
        }else
            return $this->render('search/ficha_musica.html.twig', [
                'musica' => null
            ]);
    }

    //Eliminar cualquier musica segun la ID  Prueba: http://127.0.0.1:8080/musica/delete/1
    #[Route('/musica/delete/{id}', name: 'eliminar_musica')]
    public function delete(ManagerRegistry $doctrine, $id): Response{
        $entityManager = $doctrine->getManager();
        $repositorio = $doctrine->getRepository(Musica::class);
        $musica = $repositorio->find($id);
        if ($musica){           
            try
            {
                $entityManager->remove($musica);
                $entityManager->flush();
                return new Response("Musica eliminada");
            } catch (\Exception $e) {
                return new Response("Error eliminado objeto");
            }  
        }else
            return $this->render('ficha_contacto.html.twig', [
                'musica' => null
            ]);  
    }
}
