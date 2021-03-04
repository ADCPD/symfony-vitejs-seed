###Tutorial Symfony/ViteJs : Utiliser ViteJs avec Symfony (Grafikart)
11:00/41:37


A partir de votre projet SF : 

Créer votre projet ViteJs via la command : 
`yarn yarn create @vitejs/app viteapp --template react`

Entrer dans le repo `./viteapp` ensuite créer les dependances via : 
`yarn`

Lancer votre env de dev : 
`yarn dev`

Builder votre ViteJS manifest + assset : 
````yarn build````
si vous etes sur de votre build et il y a pas risque de perdre vos libs :
```yarn build --emptyOutDir```

Créer un lien sympôlique dans `/public` pour que React reconnais les images :
`ln -s ../assets/ assets`

Vous pouvez vider le cache de votre vite_cache en utilisant la commande 
`php bin/console cache:pool:clear vite_cache_pool`
quand a déja crée au pa`r avant dans cache.yaml`