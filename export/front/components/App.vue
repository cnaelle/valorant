<template>
    <swiper :spaceBetween="30" :centeredSlides="true" :autoplay="{
        delay: 100000,
        disableOnInteraction: false,
    }" :pagination="{
    clickable: true,
}" :navigation="true" :modules="modules" class="mySwiper">
        <swiper-slide>
            <div class="grid grid-cols-2 gap-4 w-9/12 h-2/5 place-content-center bg-slate-800/80">
                <div class="m-15">
                    <img
                        src="https://images.contentstack.io/v3/assets/bltb6530b271fddd0b1/blt0bb2058c88892462/63b8a805ade3a64c67746408/Lotus_Screenshot_1.jpg?auto=webp&width=915">
                </div>
                <div class="text-white my-auto h-max">
                    Un mystérieux bâtiment traversé par un conduit astral gorgé de pouvoir antique. De grandes portes en
                    pierre
                    offrent de nombreux choix de déplacement et ouvrent l'accès à trois sites cachés.
                </div>
            </div>
        </swiper-slide>
        <swiper-slide>
            <div class="grid grid-cols-2 gap-4 w-9/12 h-2/5 place-content-center bg-slate-800/80">
                <div class="m-10">
                    <img src='https://static.wikia.nocookie.net/valorant/images/a/af/Loading_Screen_Pearl.png'>
                </div>
                <div class="text-white w-96 my-auto h-max">
                    Les attaquants fondent sur les défenseurs dans le riche décor sous-marin de cette carte en deux sites.
                    Pearl
                    n’a pas de mécanique mais dépend de ses conditions géographiques. Ouvrez le feu dans une mêlée au milieu
                    ou
                    prenez de la distance sur les flancs de notre première carte située sur la Terre Omega.
                </div>
            </div>
        </swiper-slide>
        <swiper-slide>
            <div class="grid grid-cols-2 gap-4 w-9/12 h-2/5 place-content-center bg-slate-800/80">
                <div>
                    <img src='https://static.wikia.nocookie.net/valorant/images/f/fc/Loading_Screen_Fracture.png'>
                </div>
                <div class="text-white w-96 my-auto h-max">
                    Un centre de recherche confidentiel fractionné par l'échec d\'une expérience impliquant de la radianite.
                    La
                    stratégie défensive est à l\'image de la carte : double. À vous de choisir, allez-vous chercher les
                    attaquants sur leur propre territoire ou fermer les trappes pour retarder leur assaut ?
                </div>
            </div>
        </swiper-slide>
        <swiper-slide>
            <div class="grid grid-cols-2 gap-4 w-9/12 h-2/5 place-content-center bg-slate-800/80">
                <div>
                    <img src='https://static.wikia.nocookie.net/valorant/images/1/13/Loading_Screen_Icebox.png'>
                </div>
                <div class="text-white w-96 my-auto h-max">
                    Votre prochain champ de bataille est un site d'excavation secret du Kingdom envahi par les glaces. Les
                    deux
                    installations protégées par la neige et le métal demanderont un peu de finesse horizontale. Profitez des
                    tyroliennes, et personne ne vous verra venir
                </div>
            </div>
        </swiper-slide>
        <swiper-slide>
            <div class="grid grid-cols-2 gap-4 w-9/12 h-2/5 place-content-center bg-slate-800/80">
                <div>
                    <img src='https://static.wikia.nocookie.net/valorant/images/2/23/Loading_Screen_Bind.png'>
                </div>
                <div class="text-white w-96 my-auto h-max">
                    Deux sites. Pas de milieu. Vous devez aller à gauche ou à droite. Où irez-vous ? Les deux directions
                    proposent des accès directs aux attaquants ainsi que deux téléporteurs à sens unique pour faciliter les
                    contournements.
                </div>
            </div>
        </swiper-slide>
        <swiper-slide>
            <div class="grid grid-cols-2 gap-4 w-9/12 h-2/5 place-content-center bg-slate-800/80">
                <div>
                    <img src='https://static.wikia.nocookie.net/valorant/images/7/70/Loading_Screen_Haven.png'>
                </div>
                <div class="text-white w-96 my-auto h-max">
                    Sous un monastère oublié, une clameur jaillit tandis que des agents rivaux s'affrontent pour s'emparer
                    de
                    trois sites. Le terrain à contrôler est plus grand, mais les défenseurs peuvent se servir des bâtiments
                    supplémentaires pour se montrer plus agressifs.
                </div>
            </div>
        </swiper-slide>
        <swiper-slide>
            <div class="grid grid-cols-2 gap-4 w-9/12 h-2/5 place-content-center bg-slate-800/80">
                <div>
                    <img src='https://static.wikia.nocookie.net/valorant/images/d/d6/Loading_Screen_Split.png'>
                </div>
                <div class="text-white w-96 my-auto h-max">
                    Dans la vie, si vous voulez aller loin, il faudra d'abord prendre de la hauteur. Ces deux sites sont
                    séparés par un centre surélevé qui permet des déplacements rapides grâce à deux ascendeurs en corde.
                    Chaque
                    site possède une gigantesque tour qui est essentielle pour prendre le contrôle de la zone. N'oubliez pas
                    de
                    lever les yeux au ciel avant que tout ne vole en éclats.
                </div>
            </div>
        </swiper-slide>
        <swiper-slide>
            <div class="grid grid-cols-2 gap-4 w-9/12 h-2/5 place-content-center bg-slate-800/80">
                <div>
                    <img src='https://static.wikia.nocookie.net/valorant/images/e/e7/Loading_Screen_Ascent.png'>
                </div>
                <div class="text-white w-96 my-auto h-max">
                    Un terrain de jeu ouvert, idéal pour les guerres de position et d'attrition, divise les deux sites
                    d'Ascent. Chacun d'entre eux peut être fortifié par d'épaisses portes de protection. Une fois ces
                    dernières fermées, vous n'aurez pas d'autre choix que de les détruire ou de trouver un autre chemin.
                    Cédez
                    le moins de terrain possible à vos adversaires !
                </div>
            </div>
        </swiper-slide>
        <swiper-slide>
            <div class="grid grid-cols-2 gap-4 w-9/12 h-2/5 place-content-center bg-slate-800/80">
                <div>
                    <img src='https://static.wikia.nocookie.net/valorant/images/1/10/Loading_Screen_Breeze.png'>
                </div>
                <div class="text-white w-96 my-auto h-max">
                    Dans ce paradis tropical, vous aurez tout le loisir d'admirer les vestiges historiques et les grottes
                    marines, mais prévoyez aussi de quoi vous mettre à couvert ; vous en aurez besoin dans les grandes zones
                    dégagées et pour les affrontements à longue distance. Il faut que vos défenses soient solides si vous ne
                    voulez pas qu'on vous les Breeze.
                </div>
            </div>
        </swiper-slide>
    </swiper>
</template>
<script>
// Import Swiper Vue.js components
import { Swiper, SwiperSlide } from 'swiper/vue';

// Import Swiper styles
import 'swiper/css';

import 'swiper/css/pagination';
import 'swiper/css/navigation';

import './style.css';

// import required modules
import { Autoplay, Pagination, Navigation } from 'swiper';

export default {
    components: {
        Swiper,
        SwiperSlide,
    },
    setup() {
        return {
            modules: [Autoplay, Pagination, Navigation],
        };
    },
};

</script>
  