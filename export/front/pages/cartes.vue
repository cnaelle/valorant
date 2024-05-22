<template>
  <main>
    <div class="h-screen w-full bg-gray-100/20 backdrop-blur">
      <section id="header" class="h-40 w-full bg-black/20 backdrop-blur">
        <div id="menu" class="container mx-auto flex h-full items-center justify-between px-4">
          <div>
            <img class="w-20" src="https://logodownload.org/wp-content/uploads/2020/06/valorant-logo-0.png">
          </div>
          <router-link :to="{ path: '/' }">
            <n-button type="error" class="text-gray-100 valorant">
              Tous les agents
            </n-button>
          </router-link>
          <div id="nav" class="">

          </div>
        </div>
      </section>
      <div class="mx-auto my-20">
        <App ref="mapSlide" :data="map" />
      </div>
    </div>
  </main>
</template>

<script setup>
//import Map from "/components/Map.vue"
import App from "/components/App.vue"
useHead({
  title: 'VALORANT - Cartes'
})

const mapSlide = ref(null)

function showGallery(index) {
  mapSlide.value[index].showModal = true
  console.log(mapSlide.value[index])
}

onMounted(() => {
  console.log(mapSlide.value)
})

const maps = [{
  name: 'Lotus',
  description: 'Un mystérieux bâtiment traversé par un conduit astral gorgé de pouvoir antique. De grandes portes en pierre offrent de nombreux choix de déplacement et ouvrent l\'accès à trois sites cachés.',
  img1: 'https://images.contentstack.io/v3/assets/bltb6530b271fddd0b1/blt0bb2058c88892462/63b8a805ade3a64c67746408/Lotus_Screenshot_1.jpg?auto=webp&width=915',
  img2: 'https://images.contentstack.io/v3/assets/bltb6530b271fddd0b1/blt6c6046d8572332b4/63bd8d19d91eb93e774bd429/Phase_3_Minimap_LOTUS_FR.jpg?auto=webp&width=515',
  img3: 'https://images.contentstack.io/v3/assets/bltb6530b271fddd0b1/blt0bb2058c88892462/63b8a805ade3a64c67746408/Lotus_Screenshot_1.jpg?auto=webp&width=915',
  img4: 'https://images.contentstack.io/v3/assets/bltb6530b271fddd0b1/blt4f595d14ee2ee972/63b8a8047f7baa7b8ce598a8/Lotus_Screenshot_2.jpg?auto=webp&width=915',
  img5: 'https://images.contentstack.io/v3/assets/bltb6530b271fddd0b1/blt296ca5112fed17f4/63b8a8058a5edf5b50386141/Lotus_Screenshot_4.jpg?auto=webp&width=915',
},
{
  name: 'Pearl',
  description: 'Les attaquants fondent sur les défenseurs dans le riche décor sous-marin de cette carte en deux sites. Pearl n’a pas de mécanique mais dépend de ses conditions géographiques. Ouvrez le feu dans une mêlée au milieu ou prenez de la distance sur les flancs de notre première carte située sur la Terre Omega.',
  img1: 'https://static.wikia.nocookie.net/valorant/images/a/af/Loading_Screen_Pearl.png',
  img2: 'https://images.contentstack.io/v3/assets/bltb6530b271fddd0b1/blt48c9b4a748194b85/62b2fd6a4a98d755621a683e/Pearl_Map_Website_Asset_FR.jpg?auto=webp&width=515',
  img3: 'https://images.contentstack.io/v3/assets/bltb6530b271fddd0b1/bltcf2c066dc0337049/62a28a30f4da744f1d8c60c9/Pearl_Gallery_02.jpg?auto=webp&width=915',
  img4: 'https://images.contentstack.io/v3/assets/bltb6530b271fddd0b1/blt771890dd49e137a3/62a28a398f6a4950536cea50/Pearl_Gallery_03.jpg?auto=webp&width=915',
  img5: 'https://images.contentstack.io/v3/assets/bltb6530b271fddd0b1/blt37db6f24d5ae02b8/62a28a4192725d5bf817de56/Pearl_Gallery_04.jpg?auto=webp&width=915',
},
{
  name: 'Fracture',
  description: 'Un centre de recherche confidentiel fractionné par l\'échec d\'une expérience impliquant de la radianite. La stratégie défensive est à l\'image de la carte : double. À vous de choisir, allez-vous chercher les attaquants sur leur propre territoire ou fermer les trappes pour retarder leur assaut ?',
  img1: 'https://static.wikia.nocookie.net/valorant/images/f/fc/Loading_Screen_Fracture.png',
  img2: 'https://images.contentstack.io/v3/assets/bltb6530b271fddd0b1/blt9c2aae6de6cd5c32/633c199a7837bb10ac516553/Fracture_Map_Website_Asset-507_FR.jpg?auto=webp&width=515',
  img3: 'https://images.contentstack.io/v3/assets/bltb6530b271fddd0b1/blt371ab49354f8002f/6131bedf07866d6a2d823d2c/Fracture_Screenshot-1.jpg?auto=webp&width=915',
  img4: 'https://images.contentstack.io/v3/assets/bltb6530b271fddd0b1/blt077539ab4901aaf7/6131bf103ff46b267bdfaf8a/Fracture_Screenshot-2.jpg?auto=webp&width=915',
  img5: 'https://images.contentstack.io/v3/assets/bltb6530b271fddd0b1/blt82553061d7056a29/6131bf1c85514a6ee3fac85b/Fracture_Screenshot-3.jpg?auto=webp&width=915',
},
{
  name: 'Icebox',
  description: 'Votre prochain champ de bataille est un site d\'excavation secret du Kingdom envahi par les glaces. Les deux installations protégées par la neige et le métal demanderont un peu de finesse horizontale. Profitez des tyroliennes, et personne ne vous verra venir',
  img1: 'https://static.wikia.nocookie.net/valorant/images/1/13/Loading_Screen_Icebox.png',
  img2: 'https://images.contentstack.io/v3/assets/bltb6530b271fddd0b1/blta92bbe5ce786e7ae/626c385c76b17f2f94786f9c/04-2022-Icebox-Minimap-2_FR.jpg?auto=webp&width=515',
  img3: 'https://images.contentstack.io/v3/assets/bltb6530b271fddd0b1/blta5558199bebb5107/5f80debfa50ed742e7fca4b9/icebox_3.jpg?auto=webp&width=915',
  img4: 'https://images.contentstack.io/v3/assets/bltb6530b271fddd0b1/blt5e97ee30cae1b6b6/5f80dec04671ee30c689ca24/icebox_5.jpg?auto=webp&width=915',
  img5: 'https://images.contentstack.io/v3/assets/bltb6530b271fddd0b1/bltd665c580a4b876c2/5f80debf271ecf4a1a642fe2/icebox_6.jpg?auto=webp&width=915',
},
{
  name: 'Bind',
  description: 'Deux sites. Pas de milieu. Vous devez aller à gauche ou à droite. Où irez-vous ? Les deux directions proposent des accès directs aux attaquants ainsi que deux téléporteurs à sens unique pour faciliter les contournements.',
  img1: 'https://static.wikia.nocookie.net/valorant/images/2/23/Loading_Screen_Bind.png',
  img2: 'https://images.contentstack.io/v3/assets/bltb6530b271fddd0b1/bltc46384a267347d3a/5ecb89a6c846021917ecbc11/fr-bind.png?auto=webp&width=515',
  img3: 'https://images.contentstack.io/v3/assets/bltb6530b271fddd0b1/blt4d395d5eb0c5aeec/5eabe92d97c0a55d71b7fce5/bind2.jpg?auto=webp&width=915',
  img4: 'https://images.contentstack.io/v3/assets/bltb6530b271fddd0b1/blt275a73b80ff9eb5a/5eabe92db8a6356e4ddc0c92/bind3.jpg?auto=webp&width=915',
  img5: 'https://images.contentstack.io/v3/assets/bltb6530b271fddd0b1/blt81c2ff999b2e5887/5eabe92d08d37e6d82ef715a/bind4.jpg?auto=webp&width=915',
},
{
  name: 'Haven',
  description: 'Sous un monastère oublié, une clameur jaillit tandis que des agents rivaux s\'affrontent pour s\'emparer de trois sites. Le terrain à contrôler est plus grand, mais les défenseurs peuvent se servir des bâtiments supplémentaires pour se montrer plus agressifs.',
  img1: 'https://static.wikia.nocookie.net/valorant/images/7/70/Loading_Screen_Haven.png',
  img2: 'https://images.contentstack.io/v3/assets/bltb6530b271fddd0b1/bltf4553101703d707f/5ecb8976722d20585b2f4acc/fr-haven.png?auto=webp&width=515',
  img3: 'https://images.contentstack.io/v3/assets/bltb6530b271fddd0b1/blt65a4029cdfaf77b4/5eabe9861b51e36d7c1b67ab/haven2.jpg?auto=webp&width=915',
  img4: 'https://images.contentstack.io/v3/assets/bltb6530b271fddd0b1/blt2bd976c100216469/5eabe98608d37e6d82ef7160/haven3.jpg?auto=webp&width=915',
  img5: 'https://images.contentstack.io/v3/assets/bltb6530b271fddd0b1/blt768f194cd2c52f84/5eabe9862b79652f27c326ca/haven4.jpg?auto=webp&width=915',
},
{
  name: 'Split',
  description: 'Dans la vie, si vous voulez aller loin, il faudra d\'abord prendre de la hauteur. Ces deux sites sont séparés par un centre surélevé qui permet des déplacements rapides grâce à deux ascendeurs en corde. Chaque site possède une gigantesque tour qui est essentielle pour prendre le contrôle de la zone. N\'oubliez pas de lever les yeux au ciel avant que tout ne vole en éclats.',
  img1: 'https://static.wikia.nocookie.net/valorant/images/d/d6/Loading_Screen_Split.png',
  img2: 'https://images.contentstack.io/v3/assets/bltb6530b271fddd0b1/blta0af141fd9ab82af/63bede453ae0f31fad3f4f94/Phase_3_Minimap_SPLIT_FR.jpg?auto=webp&width=515',
  img3: 'https://images.contentstack.io/v3/assets/bltb6530b271fddd0b1/blt643d33e2defb855c/5eabe9fe248a28605479c547/split1.jpg?auto=webp&width=915',
  img4: 'https://images.contentstack.io/v3/assets/bltb6530b271fddd0b1/blt8114d8ae57703cf8/5ed81946bf4ae52c761ec8e8/split2-2.jpg?auto=webp&width=915',
  img5: 'https://images.contentstack.io/v3/assets/bltb6530b271fddd0b1/bltdfd43bd79d9b3410/5eabe9fea20afe612d82f833/split3.jpg?auto=webp&width=915',
},
{
  name: 'Ascent',
  description: 'Un terrain de jeu ouvert, idéal pour les guerres de position et d\'attrition, divise les deux sites d\'Ascent. Chacun d\'entre eux peut être fortifié par d\'épaisses portes de protection. Une fois ces dernières fermées, vous n\'aurez pas d\'autre choix que de les détruire ou de trouver un autre chemin. Cédez le moins de terrain possible à vos adversaires !',
  img1: 'https://static.wikia.nocookie.net/valorant/images/e/e7/Loading_Screen_Ascent.png',
  img2: 'https://images.contentstack.io/v3/assets/bltb6530b271fddd0b1/blt8f3c2c6f9e8ada00/5ecb89024a28e119db5623cc/fr-ascent.png?auto=webp&width=515',
  img3: 'https://images.contentstack.io/v3/assets/bltb6530b271fddd0b1/blt930666d9ab927326/5eabe9c45751b2150e57a42c/ascent1.jpg?auto=webp&width=915',
  img4: 'https://images.contentstack.io/v3/assets/bltb6530b271fddd0b1/blt9e24fe356d0faf81/5eabe9c497c0a55d71b7fceb/ascent2.jpg?auto=webp&width=915',
  img5: 'https://images.contentstack.io/v3/assets/bltb6530b271fddd0b1/blt77e465b8079103af/5eabe9c4af7e315106b479a1/ascent3.jpg?auto=webp&width=915',
},
{
  name: 'Breeze',
  description: 'Dans ce paradis tropical, vous aurez tout le loisir d\'admirer les vestiges historiques et les grottes marines, mais prévoyez aussi de quoi vous mettre à couvert ; vous en aurez besoin dans les grandes zones dégagées et pour les affrontements à longue distance. Il faut que vos défenses soient solides si vous ne voulez pas qu\'on vous les Breeze.',
  img1: 'https://static.wikia.nocookie.net/valorant/images/1/10/Loading_Screen_Breeze.png',
  img2: 'https://images.contentstack.io/v3/assets/bltb6530b271fddd0b1/bltda4acf03ad77d2d7/6087f4871022211019cca6e1/Breeze_Map_Website_Asset-fre.jpg?auto=webp&width=515',
  img3: 'https://images.contentstack.io/v3/assets/bltb6530b271fddd0b1/bltde7c2fc29aa0d3a0/607f9e3ce650b13fbe2129d2/breeze_2.jpg?auto=webp&width=915',
  img4: 'https://images.contentstack.io/v3/assets/bltb6530b271fddd0b1/blt174b8512c8e4d6a5/607f9e3c03ce953dac7563e4/breeze_3.jpg?auto=webp&width=915',
  img5: 'https://images.contentstack.io/v3/assets/bltb6530b271fddd0b1/blt706ed4624cc5353d/607f9e3db35a7a3c69a373b8/breeze_5.jpg?auto=webp&width=915',
}
]


</script>