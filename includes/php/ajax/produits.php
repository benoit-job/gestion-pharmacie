<?php
session_start();
include('../../../../includes/php/connexion_bdd.php');
include_once('../../../../includes/php/fonctions.php');
?>

<?php
if(isset($_POST['listeProduits']))
{
	$nbreProduit = strip_tags(htmlspecialchars(trim($_POST['nbreProduit'])));

	$query = "SELECT *
	          FROM produits
	          WHERE id_site = '".$_SESSION["site"]["id"]."' AND 
	                statut IN ('visible','non visible') 
	          ORDER BY nom, id 
	          LIMIT ".$nbreProduit.", 20";
	$resultat = mysqli_query($bdd, $query) or die("Requête non conforme");  
	$ligne = 0;
	while($produit = mysqli_fetch_array($resultat))
	{
	  echo "<tr> 
	            <form method='post' action='produits.php'>
	                <td>
	                    <input type='checkbox' class='checkboxIdTable' onchange='getSelectedCheckboxes()' value='".$produit['id']."'>
	                </td> 
	                <td>".++$ligne."</td> 
	                <td>".affImgAdmin('60px', '60px', $produit['image'], '')."</td>  
	                <td>".ucfirst($produit['nom'])."</td>  
	                <td style='white-space: normal; min-width: 200px; max-width: 250px;'>
	                    <span class='line-clamp-2'>".affCatsProduit($bdd, $produit['id'])."</span>
	                </td>  
	                <td>".affPrixProduit($bdd, $produit['id'])."</td>  
	                <td>".$produit['statut']."</td> 
	                <td class='text-end'>
	                  <a href='produit_details.php?id_produit=".crypt_decrypt_chaine($produit['id'], 'C')."'  class='btn btn-light btn-sm'>Détails</a>

	                  <button type='submit' name='supprimerProduit' class='btn btn-light supprimer btn-sm'  onclick=\"return confirm('Voulez-vous supprimer ?')\"><i class='fas fa-trash'></i></button>
	                </td> 
	                <input type='hidden' name='id_produit' value='".crypt_decrypt_chaine($produit['id'], 'C')."'>
	            </form> 
	        </tr>"; 
	}
}
?>