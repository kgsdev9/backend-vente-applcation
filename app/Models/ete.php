use Illuminate\Support\Str;

class Etude extends Model
{
    protected $table = 'etudes'; // Nom de la table

    // Fonction pour générer un numéro unique pour 'etudeetudeclient'
    public static function generateUniqueEtudeNumber()
    {
        // Générer un identifiant unique avec un préfixe (par exemple : "ETU-")
        $etudeNumber = 'ETU-' . Str::upper(Str::random(8));

        // Vérifier si ce numéro existe déjà dans la base de données
        while (self::where('etudeetudeclient', $etudeNumber)->exists()) {
            // Si le numéro existe, en générer un nouveau
            $etudeNumber = 'ETU-' . Str::upper(Str::random(8));
        }

        // Retourner un numéro unique
        return $etudeNumber;
    }

    // Utiliser la méthode dans l'événement 'creating' pour attribuer le numéro avant de sauvegarder l'enregistrement
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($etude) {
            $etude->etudeetudeclient = self::generateUniqueEtudeNumber();
        });
    }
}
