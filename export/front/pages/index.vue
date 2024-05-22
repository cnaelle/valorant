<template>
  <main>
    <div class="bg-scroll bg-contain"
      style="background-image: url(https://wallpapers.com/images/featured/305kescxw5dpup7y.jpg)">
      <section id="header" class="h-40 w-full bg-black/20 backdrop-blur">
        <div id="menu" class="container mx-auto flex h-full items-center justify-between px-4">
          <div>
            <img class="w-20" src="https://seeklogo.com/images/V/valorant-logo-FAB2CA0E55-seeklogo.com.png">
          </div>
          <router-link :to="{ path: '/cartes' }">
            <n-button strong secondary type="error" class=" text-gray-100 valorant">
              Toutes les cartes
            </n-button>
          </router-link>
          <div id="nav" class="flex gap-x-4">
            <button id="controleur" class="text-white valorant" @click="afficher('controleur')">
              <img
                src="https://images.contentstack.io/v3/assets/bltb6530b271fddd0b1/blt63e21ca9443dce8e/5eb270f43b09c042ddca1353/Controller.png"
                class="w-10 mx-auto pb-3">
              CONTROLEUR
            </button>
            <button id="duelliste" class="text-white valorant" @click="afficher('duelliste')">
              <img
                src="https://images.contentstack.io/v3/assets/bltb6530b271fddd0b1/blt213441880cf2cdf5/5eaa06851b51e36d7c1b61d4/Duelist.png"
                class="w-10 mx-auto pb-3">
              DUELLISTE
            </button>
            <button id="initiateur" class="text-white valorant" @click="afficher('initiateur')">
              <img
                src="https://images.contentstack.io/v3/assets/bltb6530b271fddd0b1/blt2965c8a8dce0467d/5eaa0685e6f6795e530a1cbe/Initiator.png"
                class="w-10 mx-auto pb-3">
              INITIATEUR
            </button>
            <button id="sentinelle" class="text-white valorant" @click="afficher('sentinelle')">
              <img
                src="https://images.contentstack.io/v3/assets/bltb6530b271fddd0b1/blt2965c8a8dce0467d/5eaa0685e6f6795e530a1cbe/Initiator.png"
                class="w-10 mx-auto pb-3">
              SENTINELLE
            </button>
          </div>
        </div>
      </section>
      <div id="cards"
        class="grid grid-cols-3 grid-rows-7 gap-4 px-auto justify-items-center h-full py-20 container mx-auto">
        <div id="card" v-for="(agent, index) in agentsToDisplay" @click="showSpells(index)"
          class="rounded bg-black/40 py-8 w-96 h-fit m-10 backdrop-blur my-auto hover:bg-white/30 duration-400">
          <AgentCard ref="agentCard" :data="agent" />
        </div>
      </div>
    </div>
  </main>
</template>

<script setup>
import AgentCard from "/components/AgentCard.vue"
import { onMounted } from 'vue'

// return response.data.value.data[index].displayName

const { data: agents } = await useAsyncData(() => $fetch('https://valorant-api.com/v1/agents'), {
  transform: (res) => res.data

})

useHead({
  title: 'VALORANT - Agents'
})

const agentCard = ref(null)

// const agents = [
//   {
//     name: 'Jett',
//     img: 'https://images.contentstack.io/v3/assets/bltb6530b271fddd0b1/bltceaa6cf20d328bd5/5eb7cdc1b1f2e27c950d2aaa/V_AGENTS_587x900_Jett.png',
//     description: 'Représentante de sa patrie, la Corée du Sud, Jett dispose d\'un style de combat basé sur l\'agilité et l\'esquive, qui lui permet de prendre des risques qu\'elle seule peut se permettre de prendre. Elle tourne autour des affrontements et découpe ses ennemis avant même qu\'ils ne s\'en rendent compte.',
//     category: 'duelliste',
//     aImg: 'https://images.contentstack.io/v3/assets/bltb6530b271fddd0b1/bltce7928301a67a33a/5eaf861103f6e72ff388cc20/TX_Jett_Q.png',
//     aName: 'COURANT ASCENDANT',
//     aDesc: 'Vous vous propulsez INSTANTANÉMENT dans les airs.',
//     eImg: 'https://images.contentstack.io/v3/assets/bltb6530b271fddd0b1/blta0beeaa4a7e1ed78/5eaf8611b8a6356e4ddc1013/TX_Jett_E.png',
//     eName: 'VENT ARRIÈRE',
//     eDesc: 'ACTIVEZ pour préparer une rafale de vent pendant une durée limitée. RÉUTILISEZ la compétence pour vous propulser dans la direction vers laquelle vous vous dirigez. Si vous êtes immobile, vous vous propulsez vers l\'avant.',
//     cImg: 'https://images.contentstack.io/v3/assets/bltb6530b271fddd0b1/bltf137993847c71770/5eaf8611d4b10d15d3e8db4e/TX_Jett_C.png',
//     cName: 'AVERSE',
//     cDesc: 'Lancez INSTANTANÉMENT un projectile. Lorsqu\'il touche une surface, il libère un nuage qui bloque la vision pendant un court instant. MAINTENEZ la touche de la compétence pour courber la fumée dans la direction de votre réticule.',
//     xImg: 'https://images.contentstack.io/v3/assets/bltb6530b271fddd0b1/bltb3e956f9fb96318e/5eaf86112b79652f27c32a06/TX_Jett_X.png',
//     xName: 'TEMPÊTE DE LAMES',
//     xDesc: 'ÉQUIPEZ-vous d\'un ensemble de couteaux extrêmement précis qui se rechargent lorsque vous éliminez un ennemi. TIREZ pour lancer un seul couteau sur votre cible. Utilisez le TIR SECONDAIRE pour lancer toutes les dagues restantes sur votre cible.',
//   },
//   {
//     name: 'Raze',
//     img: 'https://images.contentstack.io/v3/assets/bltb6530b271fddd0b1/blt6fef56a8182d0a81/5ebf2c2798f79d6925dbd6b4/V_AGENTS_587x900_ALL_Raze_2.png',
//     description: 'Armée de sa personnalité et de sa grosse artillerie, Raze fait une entrée explosive depuis le Brésil. Grâce à sa force brute, elle excelle à débusquer les ennemis retranchés et à faire le ménage dans les espaces étroits, le tout avec une bonne dose de « boum ».',
//     category: 'duelliste',
//     aName: 'PACK EXPLOSIF',
//     aDesc: 'Lancez INSTANTANÉMENT un pack explosif qui se colle aux surfaces. RÉUTILISEZ la compétence pour déclencher l\'explosion, ce qui blesse et déplace tous ceux pris dans le souffle. Raze n\'est pas blessée par cette compétence, mais elle subit des dégâts de chute si elle tombe d\'assez haut.',
//     eName: 'GRENADE GIGOGNE',
//     eDesc: 'ÉQUIPEZ-vous d\'une grenade à sous-munitions. TIREZ pour lancer la grenade. Elle inflige des dégâts et crée des sous-munitions qui blessent tous ceux qui sont à leur portée.',
//     cName: 'BOUM BOT',
//     cDesc: 'ÉQUIPEZ-vous d\'un Boum Bot. TIREZ pour déployer le bot, ce qui le propulse en ligne droite sur le sol. Il rebondit contre les murs. Les Boum Bots se verrouillent sur les ennemis situés dans un cône face à eux et les chassent, explosant quand ils les atteignent en infligeant de lourds dégâts.',
//     xName: 'BOUQUET FINAL',
//     xDesc: 'ÉQUIPEZ-vous d\'un lance-roquettes. TIREZ pour lancer une roquette qui inflige de lourds dégâts de zone au premier contact.',
//     aImg: 'https://liquipedia.net/commons/images/0/04/Raze_Blast_Pack.png',
//     eImg: 'https://liquipedia.net/commons/images/5/59/Raze_Paint_Sheels.png',
//     cImg: 'https://liquipedia.net/commons/images/8/8d/Raze_Boom_Bot.png',
//     xImg: 'https://liquipedia.net/commons/images/5/5c/Raze_Showstopper.png',
//   },
//   {
//     name: 'Phoenix',
//     img: 'https://images.contentstack.io/v3/assets/bltb6530b271fddd0b1/bltf0200e1821b5b39f/5eb7cdc144bf8261a04d87f9/V_AGENTS_587x900_Phx.png',
//     description: 'En provenance du Royaume-Uni, Phoenix illumine le champ de bataille avec ses pouvoirs astraux et son style de combat flamboyant. Peu importe que les renforts arrivent ou non, il fonce au combat quand il le décide.',
//     category: 'duelliste',
//     aName: 'BALLE COURBE',
//     aDesc: 'ÉQUIPEZ-vous d\'un orbe flamboyant qui explose peu après avoir été lancé, aveuglant tous les joueurs qui le regardent. TIREZ pour courber la trajectoire de l\'orbe vers la gauche. Utilisez le TIR SECONDAIRE pour courber la trajectoire de l\'orbe vers la droite.',
//     eName: 'MAINS BRÛLANTES',
//     eDesc: 'ÉQUIPEZ-vous d\'une boule de feu. TIREZ pour lancer une boule de feu qui explose après un certain temps ou en touchant le sol, créant une zone enflammée persistante qui blesse les ennemis.',
//     cName: 'BRASIER',
//     cDesc: 'ÉQUIPEZ-vous d\'un mur de feu. TIREZ pour créer une ligne de feu qui se déplace vers l\'avant, créant un mur de feu qui bloque la vision et blesse les joueurs qui le traversent. MAINTENEZ LE TIR pour courber le mur dans la direction de votre réticule.',
//     xName: 'REVANCHE',
//     xDesc: 'Placez INSTANTANÉMENT un marqueur sur votre position. Tant que cette compétence est active, mourir vous renvoie à cette position avec tous vos PV (même chose si la compétence expire).',
//     aImg: 'https://liquipedia.net/commons/images/b/b3/Phoenix_Curveball.png',
//     eImg: 'https://liquipedia.net/commons/images/c/cd/Phoenix_Hot_Hands.png',
//     cImg: 'https://liquipedia.net/commons/images/b/b5/Phoenix_Blaze.png',
//     xImg: 'https://liquipedia.net/commons/images/1/1b/Phoenix_Run_it_Back.png',
//   },

//   {
//     name: 'Reyna',
//     img: 'https://images.contentstack.io/v3/assets/bltb6530b271fddd0b1/blt6577b1f58530e6b2/5eb7cdc121a5027d77420208/V_AGENTS_587x900_Reyna.png',
//     description: 'Originaire du cœur du Mexique, Reyna est une experte des combats singuliers qui se renforce à chaque élimination qu\'elle réussit. Son efficacité n\'est limitée que par son habileté, ce qui la rend très dépendante de ses propres performances.',
//     category: 'duelliste',
//     aName: 'DÉVORATION',
//     aDesc: 'Les ennemis tués par Reyna laissent des orbes d\'âme qui durent 3 sec. Vous consommez INSTANTANÉMENT un orbe d\'âme proche pour rapidement regagner des PV pendant un court instant. Au-delà de 100 PV, les PV ainsi gagnés sont perdus progressivement. Si IMPÉRATRICE est active, cette compétence se lancera automatiquement et ne consommera pas d\'orbe.',
//     eName: 'REBUFFADE',
//     eDesc: 'Vous consommez INSTANTANÉMENT un orbe d\'âme proche et devenez intangible pendant un court instant. Si IMPÉRATRICE est active, vous devenez également invisible.',
//     cName: 'ŒILLADE',
//     cDesc: 'ÉQUIPEZ-vous d\'un œil éthéré destructible. ACTIVEZ pour lancer l\'œil vers l\'avant sur une courte distance. L\'œil rend myopes tous les ennemis qui le regardent.',
//     xName: 'IMPÉRATRICE',
//     xDesc: 'Entrez INSTANTANÉMENT en furie, ce qui augmente considérablement vos vitesses de tir, de changement d\'arme et de rechargement. Réussir une élimination réinitialise la durée.',
//     aImg: 'https://liquipedia.net/commons/images/thumb/d/d4/Reyna_Devour.png/128px-Reyna_Devour.png',
//     eImg: 'https://liquipedia.net/commons/images/1/12/Reyna_Dismiss.png',
//     cImg: 'https://liquipedia.net/commons/images/thumb/9/9f/Reyna_Leer.png/128px-Reyna_Leer.png',
//     xImg: 'https://liquipedia.net/commons/images/thumb/0/0b/Reyna_Empress.png/128px-Reyna_Empress.png',
//   },
//   {
//     name: 'Breach',
//     img: 'https://images.contentstack.io/v3/assets/bltb6530b271fddd0b1/blt100d13bfa8286a3d/5eb7cdc11ea0c32e33b95fa2/V_AGENTS_587x900_Breach.png',
//     description: 'Breach, le Suédois bionique, tire de puissantes décharges cinétiques pour ouvrir un chemin en territoire ennemi. Grâce aux dégâts et aux diversions ainsi provoqués, aucun combat n\'est jamais en sa défaveur.',
//     category: 'initiateur',
//     aName: 'POINT D\'IGNITION',
//     aDesc: 'ÉQUIPEZ-vous d\'une charge aveuglante. TIREZ pour provoquer une explosion rapide à travers le mur. La charge explose en aveuglant tous les joueurs qui la regardent.',
//     eName: 'LIGNE DE FRACTURE',
//     eDesc: 'ÉQUIPEZ-vous d\'un explosif sismique. MAINTENEZ LE TIR pour augmenter la distance. RELÂCHEZ pour provoquer un séisme qui désoriente tous les joueurs dans la zone et sur une ligne menant à la zone.',
//     cName: 'RÉPLIQUE',
//     cDesc: 'ÉQUIPEZ-vous d\'une charge à fusion. TIREZ pour provoquer une explosion lente à travers le mur. L\'explosion inflige d\'importants dégâts à tous ceux dans la zone.',
//     xName: 'ONDE SISMIQUE',
//     xDesc: 'ÉQUIPEZ-vous d\'une charge sismique. TIREZ pour envoyer un séisme déferlant à travers tous les obstacles dans une large zone conique. Le séisme désoriente et projette en l\'air tous ceux qu\'il touche.',
//     aImg: 'https://liquipedia.net/commons/images/thumb/3/39/Breach_Flashpoint.png/128px-Breach_Flashpoint.png',
//     eImg: 'https://liquipedia.net/commons/images/thumb/2/27/Breach_Fault_Line.png/128px-Breach_Fault_Line.png',
//     cImg: 'https://liquipedia.net/commons/images/thumb/9/94/Breach_Aftershock.png/128px-Breach_Aftershock.png',
//     xImg: 'https://liquipedia.net/commons/images/thumb/6/6e/Breach_Rolling_Thunder.png/128px-Breach_Rolling_Thunder.png',
//   },
//   {
//     name: 'Omen',
//     img: 'https://images.contentstack.io/v3/assets/bltb6530b271fddd0b1/blt4e5af408cc7a87b5/5eb7cdc17bedc8627eff8deb/V_AGENTS_587x900_Omen.png',
//     description: 'Véritable fantôme d\'un souvenir, Omen chasse dans les ténèbres. Il aveugle les ennemis, se téléporte d\'un bout à l\'autre du champ de bataille et laisse la peur se répandre parmi ses adversaires qui se demandent qui sera sa prochaine victime.',
//     category: 'controleur',
//     aName: 'PARANOÏA',
//     aDesc: 'Tirez INSTANTANÉMENT un projectile d\'ombre vers l\'avant, ce qui réduit brièvement la portée de la vision de tous les joueurs touchés. Ce projectile peut traverser les murs.',
//     eName: 'VOILE SOMBRE',
//     eDesc: 'ÉQUIPEZ-vous d\'un orbe d\'ombre accompagné d\'un indicateur de portée. TIREZ pour lancer l\'orbe à l\'endroit marqué, ce qui crée une sphère d\'ombre durable qui bloque la vision. MAINTENEZ LE TIR SECONDAIRE tout en visant pour éloigner le marqueur. MAINTENEZ la touche de la compétence tout en visant pour rapprocher le marqueur.',
//     cName: 'VOIE DES OMBRES',
//     cDesc: 'ÉQUIPEZ-vous d\'une compétence de marche des ombres accompagnée d\'un indicateur de portée. TIREZ pour commencer une courte canalisation avant de vous téléporter vers l\'endroit marqué.',
//     xName: 'DEPUIS LES OMBRES',
//     xDesc: 'ÉQUIPEZ-vous d\'une carte tactique. TIREZ pour commencer à vous téléporter vers l\'endroit sélectionné. Pendant la téléportation, vous apparaissez sous forme d\'ombre. Les ennemis peuvent détruire cette ombre pour annuler votre téléportation.',
//     aImg: 'https://liquipedia.net/commons/images/1/1b/Omen_Paranoia.png',
//     eImg: 'https://liquipedia.net/commons/images/9/9f/Omen_Dark_Cover.png',
//     cImg: 'https://liquipedia.net/commons/images/1/10/Omen_Shrouded_Step.png',
//     xImg: 'https://liquipedia.net/commons/images/3/38/Omen_From_the_Shadows.png',
//   },
//   {
//     name: 'Viper',
//     img: 'https://images.contentstack.io/v3/assets/bltb6530b271fddd0b1/bltc825c6589eda7717/5eb7cdc6ee88132a6f6cfc25/V_AGENTS_587x900_Viper.png',
//     description: 'Viper est une chimiste américaine qui déploie un arsenal d\'appareils toxiques pour contrôler le champ de bataille et handicaper la vision des ennemis. Si les toxines ne suffisent pas à abattre sa proie, ses machinations finiront le travail.',
//     category: 'controleur',
//     aName: 'NUAGE DE POISON',
//     aDesc: 'ÉQUIPEZ-vous d\'un diffuseur de gaz. TIREZ pour lancer le diffuseur, qui reste présent jusqu\'à la fin de la manche. RÉUTILISEZ la compétence pour créer un nuage de fumée toxique qui consomme du carburant. Cette compétence peut être RÉUTILISÉE plus d\'une fois et le diffuseur peut être ramassé pour être REDÉPLOYÉ.',
//     eName: 'ÉCRAN TOXIQUE',
//     eDesc: 'ÉQUIPEZ-vous d\'un lance-diffuseurs de gaz. TIREZ pour déployer une longue ligne de diffuseurs de gaz. RÉUTILISEZ la compétence pour créer un mur de gaz toxique qui consomme du carburant. Cette compétence peut être RÉUTILISÉE plus d\'une fois.',
//     cName: 'MORSURE DU SERPENT',
//     cDesc: 'ÉQUIPEZ-vous d\'un lance-grenades chimiques. TIREZ pour lancer une grenade qui se brise en touchant le sol, créant une zone chimique persistante qui blesse les ennemis.',
//     xName: 'NID DE VIPÈRES',
//     xDesc: 'ÉQUIPEZ-vous d\'un vaporisateur chimique. TIREZ pour vaporiser un nuage chimique tout autour de Viper, créant un large nuage qui réduit la portée de la vision et les PV max des joueurs qui se trouvent dedans.',
//     aImg: 'https://liquipedia.net/commons/images/9/95/Viper_Poison_Cloud.png',
//     eImg: 'https://liquipedia.net/commons/images/a/a7/Viper_Toxic_Screen.png',
//     cImg: 'https://liquipedia.net/commons/images/9/96/Viper_Snakebite.png',
//     xImg: 'https://liquipedia.net/commons/images/0/0a/Viper_Viper%27s_Pit.png',
//   },
//   {
//     name: 'Astra',
//     img: 'https://images.contentstack.io/v3/assets/bltb6530b271fddd0b1/blt5599d0d810824279/6036ca30ce4a0d12c3ec1dfa/V_AGENTS_587x900_Astra.png',
//     description: 'L\'agent ghanéen Astra canalise les énergies du cosmos pour façonner le champ de bataille à sa convenance. Avec une maîtrise complète de sa forme astrale et un talent pour la planification stratégique, elle a toujours une large avance sur ses ennemis.',
//     category: 'controleur',
//     aName: 'IMPULSION NOVA',
//     aDesc: 'Placez des étoiles sous sa forme astrale (X) ACTIVEZ une étoile pour faire exploser une Impulsion Nova. L\'Impulsion Nova se charge brièvement avant de détonner, désorientant tous les joueurs dans son rayon d\'effet.',
//     eName: 'NÉBULEUSE',
//     eDesc: 'Placez des étoiles sous sa forme astrale (X) ACTIVEZ une étoile pour la transformer en Nébuleuse (fumée). Utilisez (F) sur une étoile pour la dissiper, ce qui vous permettra de la replacer à un autre endroit après un délai. Dissipation forme brièvement une fausse nébuleuse à l\'emplacement de l\'étoile avant de la rappeler.',
//     cName: 'PUITS DE GRAVITÉ',
//     cDesc: 'Placez des étoiles sous sa forme astrale (X) ACTIVEZ une étoile pour former un Puits de gravité. Les joueurs dans la zone sont attirés vers le centre avant qu\'il n\'explose, ce qui applique l\'altération d\'état Fragile à tous les joueurs.',
//     xName: 'FORME ASTRALE / DIVISION COSMIQUE',
//     xDesc: 'ACTIVEZ (X) pour passer en forme astrale, ce qui permet de placer des étoiles avec votre TIR PRINCIPAL. Les étoiles peuvent être activées plus tard pour être transformées en Impulsion Nova, en Nébuleuse ou en Puits de gravité. Lorsque Division cosmique est chargée, utilisez le TIR SECONDAIRE en forme astrale pour ajuster votre visée, puis le TIR PRINCIPAL pour choisir deux emplacements. Une Division cosmique infinie reliera alors les deux emplacements sélectionnés. Division cosmique bloque les balles et étouffe fortement les sons.',
//     aImg: 'https://liquipedia.net/commons/images/thumb/2/28/Astra_Nova_Pulse.png/128px-Astra_Nova_Pulse.png',
//     eImg: 'https://liquipedia.net/commons/images/thumb/2/2a/Astra_Nebula.png/128px-Astra_Nebula.png',
//     cImg: 'https://liquipedia.net/commons/images/thumb/8/83/Astra_Gravity_Well.png/128px-Astra_Gravity_Well.png',
//     xImg: 'https://liquipedia.net/commons/images/thumb/1/1d/Astra_Astral_Form.png/128px-Astra_Astral_Form.png',
//   },
//   {
//     name: 'Harbor',
//     img: 'https://images.contentstack.io/v3/assets/bltb6530b271fddd0b1/blt81e8a3e8c7beeaf3/634894a15e281916980f655b/Harbor_KeyArt-web.png',
//     description: 'Venu de la côte indienne, Harbor déferle sur le terrain grâce à une technologie antique qui lui permet de contrôler l\'eau. Il déchaîne des torrents bouillonnants et de terribles lames d\'eau pour protéger ses alliés et noyer ses adversaires.',
//     category: 'controleur',
//     aName: 'MARÉE HAUTE',
//     aDesc: 'ÉQUIPEZ-vous d\'un mur d\'eau. TIREZ pour lancer l\'eau vers l\'avant, parallèlement au sol. MAINTENEZ LE TIR pour la guider dans la direction du viseur, traverser le décor et créer un mur aqueux sur son passage. Utilisez le TIR SECONDAIRE tout en guidant l\'eau pour arrêter la compétence prématurément. Les joueurs touchés sont RALENTIS.',
//     eName: 'DÔME AQUEUX',
//     eDesc: 'ÉQUIPEZ un bouclier d\'eau sphérique. TIREZ pour le lancer. Utilisez le TIR SECONDAIRE pour faire un lancer par en dessous. Au contact du sol, il déploie un bouclier destructible capable d\'arrêter les balles.',
//     cName: 'CASCADE',
//     cDesc: 'ÉQUIPEZ-vous d\'une vague. TIREZ pour la déployer devant vous à travers les murs. RÉUTILISEZ pour empêcher la vague d\'aller plus loin. Les joueurs touchés sont RALENTIS.',
//     xName: 'GEYSERS DILUVIENS',
//     xDesc: 'ÉQUIPEZ toute la puissance de votre artéfact. TIREZ pour invoquer une flaque bouillonnante sur le sol. Les ennemis dans la zone sont frappés par des geysers successifs. Les joueurs touchés par un jet sont DÉSORIENTÉS.',
//     aImg: 'https://liquipedia.net/commons/images/thumb/6/6d/Harbor_Cove.png/128px-Harbor_Cove.png',
//     eImg: 'https://liquipedia.net/commons/images/thumb/8/85/Harbor_High_Tide.png/128px-Harbor_High_Tide.png',
//     cImg: 'https://liquipedia.net/commons/images/thumb/5/5f/Harbor_Cascade.png/128px-Harbor_Cascade.png',
//     xImg: 'https://liquipedia.net/commons/images/thumb/2/24/Harbor_Reckoning.png/128px-Harbor_Reckoning.png',
//   },
//   {
//     name: 'Cypher',
//     img: 'https://images.contentstack.io/v3/assets/bltb6530b271fddd0b1/blt158572ec37653cf3/5eb7cdc19df5cf37047009d1/V_AGENTS_587x900_Cypher.png',
//     description: 'Informateur originaire du Maroc, Cypher est un véritable réseau de surveillance à lui tout seul. Il révèle tous les secrets. Il détecte toutes les manœuvres. Rien n\'échappe à Cypher.',
//     category: 'sentinelle',
//     aName: 'CYBERCAGE',
//     aDesc: 'Lancez INSTANTANÉMENT la cybercage devant Cypher. ACTIVEZ pour créer une zone qui bloque la vision et qui ralentit les ennemis qui la traversent.',
//     eName: 'CAMÉRA ESPIONNE',
//     eDesc: 'ÉQUIPEZ-vous d\'une caméra espionne. TIREZ pour placer la caméra espionne à l\'endroit ciblé. RÉUTILISEZ cette compétence pour prendre le contrôle de la caméra. Quand vous contrôlez la caméra, TIREZ pour envoyer une fléchette de marquage. Cette fléchette révélera la position de tout joueur qu\'elle a touché.',
//     cName: 'FIL DE DÉTENTE',
//     cDesc: 'ÉQUIPEZ-vous d\'un fil de détente. TIREZ pour placer un fil de détente dissimulé et destructible, créant une ligne qui va du point de placement au mur opposé. Les joueurs ennemis qui franchissent le fil de détente seront attachés, révélés et désorientés après un court instant s\'ils ne détruisent pas le dispositif dans les temps. Le fil peut être ramassé pour être REDÉPLOYÉ.',
//     xName: 'VOL NEURAL',
//     xDesc: 'Utilisez cette compétence INSTANTANÉMENT sur un cadavre ennemi pour révéler la position de tous les joueurs ennemis en vie.',
//     aImg: 'https://liquipedia.net/commons/images/b/b9/Cypher_Cyber_Cage.png',
//     eImg: 'https://liquipedia.net/commons/images/d/d9/Cypher_Spycam.png',
//     cImg: 'https://liquipedia.net/commons/images/e/e8/Cypher_Trapwire.png',
//     xImg: 'https://liquipedia.net/commons/images/2/2a/Cypher_Neural_Theft.png',
//   },
//   {
//     name: 'Killjoy',
//     img: 'https://images.contentstack.io/v3/assets/bltb6530b271fddd0b1/blt53405c26141beff8/5f21fda671ec397ef9bf0894/V_AGENTS_587x900_KillJoy_.png',
//     description: 'Le génie à l\'allemande. Killjoy assure le contrôle facile du terrain grâce à son armée d\'inventions. Si son équipement ne suffit pas à arrêter l\'ennemi, ce sont les entraves de ses robots qui en feront du menu fretin.',
//     category: 'sentinelle',
//     aName: 'BOT-ALARME',
//     aDesc: 'ÉQUIPEZ-vous d\'un robot d\'alarme camouflé. TIREZ pour déployer un robot qui traque les ennemis à sa portée. Quand il atteint sa cible, le robot explose, infligeant l\'altération d\'état Vulnérable. MAINTENEZ LA TOUCHE D\'ÉQUIPEMENT pour rappeler un robot déployé.',
//     eName: 'TOURELLE',
//     eDesc: 'ÉQUIPEZ-vous d\'une tourelle. TIREZ pour déployer une tourelle qui tire sur les ennemis dans un cône de 180 degrés. MAINTENEZ LA TOUCHE D\'ÉQUIPEMENT pour rappeler la tourelle déployée.',
//     cName: 'ESSAIM DE NANITES',
//     cDesc: 'ÉQUIPEZ-vous d\'une grenade à essaim de nanites. TIREZ pour lancer la grenade. À l\'atterrissage, la grenade se camoufle. ACTIVEZ la grenade pour déployer un essaim de nanites.',
//     xName: 'CONFINEMENT',
//     xDesc: 'ÉQUIPEZ-vous d\'un dispositif de confinement. TIREZ pour déployer le dispositif. Après un long délai d\'activation, le dispositif retient tous les ennemis pris dans sa zone d\'effet. Le dispositif peut être détruit par les ennemis.',
//     aImg: 'https://liquipedia.net/commons/images/thumb/6/6b/Killjoy_Alarmbot.png/128px-Killjoy_Alarmbot.png',
//     eImg: 'https://liquipedia.net/commons/images/thumb/4/4a/Killjoy_Turret.png/128px-Killjoy_Turret.png',
//     cImg: 'https://liquipedia.net/commons/images/thumb/5/51/Killjoy_Nanoswarm.png/128px-Killjoy_Nanoswarm.png',
//     xImg: 'https://liquipedia.net/commons/images/thumb/8/8e/Killjoy_Lockdown.png/128px-Killjoy_Lockdown.png',
//   },
//   {
//     name: 'Chamber',
//     img: 'https://images.contentstack.io/v3/assets/bltb6530b271fddd0b1/blt6f1392b30784e029/618d9da0d380f814d61f001c/WebUpdate_Chamber_KeyArt.png',
//     description: 'Aussi classe que bien équipé, le concepteur d\'armes Chamber repousse les assaillants avec une précision mortelle. Il met à profit son arsenal bien particulier pour tenir sa position et éliminer les ennemis de loin en prévoyant une solution aux défis posés par chaque stratégie.',
//     category: 'sentinelle',
//     aName: 'CHASSEUR DE TÊTES',
//     aDesc: 'ACTIVEZ pour équiper un gros calibre. Faites un TIR SECONDAIRE avec le pistolet équipé pour utiliser le viseur.',
//     eName: 'RENDEZ-VOUS',
//     eDesc: 'PLACEZ une balise de téléportation. Quand vous vous trouvez sur le sol à portée de la balise, RÉACTIVEZ pour vous y téléporter rapidement. La balise peut être ramassée pour être REDÉPLOYÉE.',
//     cName: 'MARQUE DÉPOSÉE',
//     cDesc: 'POSEZ un piège qui détecte les ennemis. Quand un ennemi visible est à portée, le piège déclenche un compte à rebours avant d\'ébranler le terrain autour de lui, créant un champ persistant qui ralentit les joueurs qui s\'y trouvent. Le piège peut être ramassé pour être REDÉPLOYÉ.',
//     xName: 'TOUR DE FORCE',
//     xDesc: 'ACTIVEZ pour matérialiser un puissant fusil de sniper personnalisé qui tue l\'ennemi en un coup. Tuer un ennemi crée un champ persistant qui ralentit les joueurs qui s\'y trouvent.',
//     aImg: 'https://liquipedia.net/commons/images/thumb/f/fa/Chamber_Headhunter.png/128px-Chamber_Headhunter.png',
//     eImg: 'https://liquipedia.net/commons/images/thumb/5/51/Chamber_Rendezvous.png/128px-Chamber_Rendezvous.png',
//     cImg: 'https://liquipedia.net/commons/images/thumb/2/2c/Chamber_Trademark.png/128px-Chamber_Trademark.png',
//     xImg: 'https://liquipedia.net/commons/images/thumb/2/2f/Chamber_Tour_De_Force.png/128px-Chamber_Tour_De_Force.png',
//   },
//   {
//     name: 'Skye',
//     img: 'https://images.contentstack.io/v3/assets/bltb6530b271fddd0b1/blt302fcb2b9628c376/5f7fa6ff8db9ea0f149ece0a/V_AGENTS_587x900_ALL_Skye.png',
//     description: 'Originaire d\'Australie, Skye et sa bande de bêtes sauvages ouvrent la voie à travers les territoires hostiles. Grâce à ses créations qui entravent l\'ennemi et à sa faculté à soigner les autres, l\'équipe est plus forte et plus en sécurité quand elle compte Skye dans ses rangs.',
//     category: 'initiateur',
//     aName: 'ÉCLAIREUR',
//     aDesc: 'ÉQUIPEZ-vous d\'une breloque Tigre de Tasmanie. TIREZ pour lancer et prendre le contrôle du prédateur. Lorsque vous le contrôlez, TIREZ pour bondir en avant, ce qui provoque une explosion qui désoriente et blesse les ennemis directement touchés.',
//     eName: 'GUIDE ÉCLATANT',
//     eDesc: 'ÉQUIPEZ-vous d\'une breloque Faucon. TIREZ pour lancer l\'oiseau devant vous. MAINTENEZ LE TIR pour guider le faucon dans la direction de votre réticule. RÉUTILISEZ la compétence pendant que le faucon est dans les airs pour le transformer en lumière qui envoie une confirmation d\'impact si un ennemi était à portée et en ligne de mire.',
//     cName: 'REVITALISATION',
//     cDesc: 'ÉQUIPEZ-vous d\'une breloque de soin. MAINTENEZ LE TIR pour effectuer une canalisation qui soigne les alliés à portée et dans la ligne de mire. Peut être réutilisé jusqu\'à épuisement de la capacité de soin. Skye ne peut pas se soigner.',
//     xName: 'TRAQUEURS',
//     xDesc: 'ÉQUIPEZ-vous d\'une breloque Traqueurs. TIREZ pour lancer trois traqueurs qui pourchasseront les trois ennemis les plus proches. Si un traqueur atteint sa cible, il réduit le champ de vision de celle-ci.',
//     aImg: 'https://liquipedia.net/commons/images/f/fd/Skye_Trailblazer.png',
//     eImg: 'https://liquipedia.net/commons/images/8/8a/Skye_Guiding_Light.png',
//     cImg: 'https://liquipedia.net/commons/images/5/5b/Skye_Regrowth.png',
//     xImg: 'https://liquipedia.net/commons/images/b/b2/Skye_Ultimate.png',
//   },
//   {
//     name: 'Brimstone',
//     img: 'https://images.contentstack.io/v3/assets/bltb6530b271fddd0b1/blt26fcf1b5752514ee/5eb7cdbfc1dc88298d5d3799/V_AGENTS_587x900_Brimstone.png',
//     description: 'Tout droit venu des États-Unis d\'Amérique, Brimstone possède un arsenal orbital qui permet à son escouade de toujours avoir l\'avantage. La précision et la portée de ses compétences utilitaires font de lui un commandant sans égal sur le terrain.',
//     category: 'controleur',
//     aName: 'BOMBE INCENDIAIRE',
//     aDesc: 'ÉQUIPEZ-vous d\'un lance-grenades incendiaires. TIREZ pour lancer une grenade qui explose lorsqu\'elle s\'arrête au sol, créant une zone enflammée persistante qui blesse les joueurs.',
//     eName: 'FRAPPE FUMIGÈNE',
//     eDesc: 'ÉQUIPEZ-vous d\'une carte tactique. TIREZ pour définir les endroits où les nuages de fumée de Brimstone atterriront. Utilisez le TIR SECONDAIRE pour confirmer, ce qui lance des nuages de fumée durables qui bloquent la vision dans la zone sélectionnée.',
//     cName: 'BALISE STIMULANTE',
//     cDesc: 'ÉQUIPEZ-vous d\'une balise stimulante. TIREZ pour lancer la balise devant Brimstone. Lorsqu\'elle touche le sol, la balise crée un champ qui confère Tir rapide aux joueurs.',
//     xName: 'RAYON ORBITAL',
//     xDesc: 'ÉQUIPEZ-vous d\'une carte tactique. TIREZ pour ordonner une frappe orbitale persistante à l\'endroit sélectionné, ce qui inflige d\'importants dégâts sur la durée aux joueurs pris dans la zone.',
//     aImg: 'https://liquipedia.net/commons/images/6/64/Brimstone_Incendiary.png',
//     eImg: 'https://liquipedia.net/commons/images/3/32/Brimstone_Sky_Smoke.png',
//     cImg: 'https://liquipedia.net/commons/images/3/3e/Brimstone_Stim_Beacon.png',
//     xImg: 'https://liquipedia.net/commons/images/b/be/Brimstone_Orbital_Strike.png',
//   },
//   {
//     name: 'Sage',
//     img: 'https://images.contentstack.io/v3/assets/bltb6530b271fddd0b1/blt8a627ec10b57f4f2/5eb7cdc16509f3370a5a93b7/V_AGENTS_587x900_sage.png',
//     description: 'Véritable pilier originaire de Chine, Sage assure sa sécurité et celle de son équipe où qu\'elle aille. Elle peut réanimer ses alliés tombés au combat et repousser les assauts ennemis pour offrir des oasis de tranquillité sur un champ de bataille infernal.',
//     category: 'sentinelle',
//     aName: 'ORBE DE LENTEUR',
//     aDesc: 'ÉQUIPEZ-vous d\'un orbe ralentissant. TIREZ pour lancer l\'orbe devant vous. Lorsqu\'il touche le sol, l\'orbe explose et crée un champ persistant qui ralentit les joueurs qui s\'y trouvent.',
//     eName: 'ORBE DE SOIN',
//     eDesc: 'ÉQUIPEZ-vous d\'un orbe de soin. TIREZ en visant un allié blessé pour lui rendre des PV sur la durée. Si vous avez subi des dégâts, utilisez le TIR SECONDAIRE pour récupérer des PV sur la durée.',
//     cName: 'ORBE BARRIÈRE',
//     cDesc: 'ÉQUIPEZ-vous d\'un orbe barrière. TIRER permet de placer un mur solide. Le TIR SECONDAIRE fait pivoter le cibleur.',
//     xName: 'RÉSURRECTION',
//     xDesc: 'ÉQUIPEZ-vous d\'une compétence de résurrection. TIREZ en visant un allié mort pour le ramener à la vie. Après une courte canalisation, votre allié est ramené à la vie avec tous ses PV.',
//     aImg: 'https://liquipedia.net/commons/images/b/b7/Sage_Slow_Orb.png',
//     eImg: 'https://liquipedia.net/commons/images/7/70/Sage_Resurrection.png',
//     cImg: 'https://liquipedia.net/commons/images/9/99/Sage_Barrier_Orb.png',
//     xImg: 'https://liquipedia.net/commons/images/7/71/Sage_Healing_Orb.png',
//   },
//   {
//     name: 'Sova',
//     img: 'https://images.contentstack.io/v3/assets/bltb6530b271fddd0b1/bltf11234f4775729b7/5ebf2c275e73766852c8d5d4/V_AGENTS_587x900_ALL_Sova_2.png',
//     description: 'Né dans l\'hiver éternel de la toundra russe, Sova traque, trouve et élimine ses ennemis avec une efficacité et une précision redoutables. Ses incroyables talents d\'éclaireur et son arc personnalisé lui garantissent que sa cible ne fuira jamais très longtemps.',
//     category: 'initiateur',
//     aName: 'ÉLECTROFLÈCHE',
//     aDesc: 'ÉQUIPEZ-vous d\'un arc et d\'une flèche électrique. TIREZ pour décocher la flèche, qui explose à l\'impact et blesse les joueurs proches. MAINTENEZ LE TIR pour augmenter la portée du projectile. Utilisez le TIR SECONDAIRE pour ajouter jusqu\'à deux rebonds à cette flèche.',
//     eName: 'FLÈCHE DE RECONNAISSANCE',
//     eDesc: 'ÉQUIPEZ-vous d\'un arc et d\'une flèche de reconnaissance. TIREZ pour décocher la flèche, qui s\'active à l\'impact et révèle la position des ennemis proches se trouvant dans sa ligne de vue. MAINTENEZ LE TIR pour augmenter la portée du projectile. Utilisez le TIR SECONDAIRE pour ajouter jusqu\'à deux rebonds à cette flèche.',
//     cName: 'DRONE RAPACE',
//     cDesc: 'ÉQUIPEZ-vous d\'un drone rapace. TIREZ pour déployer et prendre le contrôle du drone. Quand vous contrôlez le drone, TIREZ pour envoyer une fléchette de marquage. Cette fléchette révélera la position de tout joueur qu\'elle a touché.',
//     xName: 'FUREUR DU CHASSEUR',
//     xDesc: 'ÉQUIPEZ-vous d\'un arc et de trois projectiles d\'énergie à longue portée capables de traverser les murs. TIREZ pour décocher un projectile d\'énergie en ligne droite, qui inflige des dégâts et révèle la position des ennemis touchés. Cette compétence peut être RÉUTILISÉE jusqu\'à deux fois de plus tant que la compétence est active.',
//     aImg: 'https://liquipedia.net/commons/images/b/b9/Sova_Shock_Bolt.png',
//     eImg: 'https://liquipedia.net/commons/images/1/12/Sova_Recon_Bolt.png',
//     cImg: 'https://liquipedia.net/commons/images/4/40/Sova_Owl_Drone.png',
//     xImg: 'https://liquipedia.net/commons/images/6/64/Sova_Hunter%27s_Fury.png',
//   },
//   {
//     name: 'Yoru',
//     img: 'https://images.contentstack.io/v3/assets/bltb6530b271fddd0b1/bltd4080f8efb365751/5ff5660bb47cdf7fc7d6c3dc/V_AGENTS_587x900_yoru.png',
//     description: 'Le Japonais Yoru perce des trous dans la réalité pour s\'infiltrer derrière les lignes ennemies sans se faire repérer. En faisant preuve d\'autant de ruse que d\'agressivité, il prend ses cibles par surprise avant qu\'elles n\'aient le temps de se retourner.',
//     category: 'duelliste',
//     aName: 'ANGLE MORT',
//     aDesc: 'ÉQUIPEZ-vous d\'un fragment dimensionnel instable arraché à la réalité. TIREZ pour lancer le fragment, qui provoquera un éclat aveuglant peu après avoir touché une surface dure.',
//     eName: 'VISITE SURPRISE',
//     eDesc: 'ÉQUIPEZ-vous d\'une balise dimensionnelle. TIREZ pour envoyer la balise vers l\'avant. Utilisez le TIR SECONDAIRE pour poser une balise stationnaire devant vous. ACTIVEZ la balise pour vous y téléporter. UTILISEZ la balise pour feindre la téléportation.',
//     cName: 'FEINTE',
//     cDesc: 'ÉQUIPEZ-vous d\'un écho qui se transforme en double de Yoru une fois activé. TIREZ pour activer instantanément le double et l\'envoyer vers l\'avant. Utilisez le TIR SECONDAIRE pour poser un écho inactif devant vous. UTILISEZ un écho inactif pour le transformer en double et l\'envoyer vers l\'avant. Le double explose en émettant une lumière aveuglante quand les ennemis le détruisent.',
//     xName: 'TRANSFERT DIMENSIONNEL',
//     xDesc: 'ÉQUIPEZ-vous d\'un masque qui permet de voir entre les dimensions. TIREZ pour passer dans la dimension de Yoru. Les ennemis hors de la zone ne peuvent ni vous voir ni vous affecter.',
//     aImg: 'https://liquipedia.net/commons/images/b/be/Yoru_Blindside.png',
//     eImg: 'https://liquipedia.net/commons/images/a/ad/Yoru_Gatecrash.png',
//     cImg: 'https://liquipedia.net/commons/images/thumb/f/f0/Yoru_Fakeout.png/128px-Yoru_Fakeout.png',
//     xImg: 'https://liquipedia.net/commons/images/3/3c/Yoru_Dimensional_Rift.png',
//   },
//   {
//     name: 'Kay/o',
//     img: 'https://images.contentstack.io/v3/assets/bltb6530b271fddd0b1/blte5aefeb26bee12c8/60ca5aa30ece0255888d7faa/KAYO_KeyArt_587x900.png',
//     description: 'KAY/O est une machine de guerre conçue dans un but précis : neutraliser les radiants. La neutralisation des compétences ennemies réduit les possibilités de riposte des adversaires, ce qui confère un avantage décisif à son équipe.',
//     category: 'initiateur',
//     aName: 'MÉMOIRE/FLASH',
//     aDesc: 'ÉQUIPEZ-vous d\'une grenade aveuglante. TIREZ pour la lancer. La grenade explose après un court instant et aveugle tous ceux qui voient l\'explosion.',
//     eName: 'POINT/ZÉRO',
//     eDesc: 'ÉQUIPEZ-vous d\'une lame neutralisante. TIREZ pour la lancer. La lame se colle à la première surface touchée. Après un délai, elle neutralise tous ceux dans le rayon de l\'explosion.',
//     cName: 'FRAG/MENT',
//     cDesc: 'ÉQUIPEZ-vous d\'un fragment explosif. TIREZ pour le lancer. Le fragment se colle au sol et explose à plusieurs reprises, infligeant des dégâts quasi mortels au centre de chaque explosion.',
//     xName: 'CMD/NULL',
//     xDesc: 'Entrez INSTANTANÉMENT en surcharge. KAY/O est renforcé par de l\'énergie radianite polarisée et il émet de grandes vagues d\'énergie. Les ennemis touchés par ces vagues sont neutralisés pendant un court instant.',
//     aImg: 'https://liquipedia.net/commons/images/thumb/5/53/KAYO_FLASHdrive.png/128px-KAYO_FLASHdrive.png',
//     eImg: 'https://liquipedia.net/commons/images/thumb/d/d2/KAYO_ZEROpoint.png/128px-KAYO_ZEROpoint.png',
//     cImg: 'https://liquipedia.net/commons/images/thumb/9/9b/KAYO_FRAGment.png/128px-KAYO_FRAGment.png',
//     xImg: 'https://liquipedia.net/commons/images/thumb/5/5a/KAYO_NULLcmd.png/128px-KAYO_NULLcmd.png',
//   },
//   {
//     name: 'Neon',
//     img: 'https://images.contentstack.io/v3/assets/bltb6530b271fddd0b1/blt8093ba7b5e84ed05/61d8a63ddea73a236fc56d12/Neon_KeyArt-Web.png',
//     description: 'L\'agent philippin, Neon, s\'élance vers l\'avant à une vitesse fulgurante, libérant de grosses décharges de radiance biomagnétique générées frénétiquement par son corps. Elle se lance à la poursuite des ennemis qui n\'ont pas le temps de s\'y préparer et les élimine aussi vite que l\'éclair.',
//     category: 'duelliste',
//     aName: 'ÉCLAIR RELAIS',
//     aDesc: 'Lancez INSTANTANÉMENT un éclair d\'énergie qui rebondit une fois. Chaque fois qu\'il rencontre une surface, l\'éclair électrifie le sol en dessous avec un coup de jus.',
//     eName: 'VITESSE SUPÉRIEURE',
//     eDesc: 'Chargez INSTANTANÉMENT le pouvoir de Neon pour décupler sa vitesse. À la fin du chargement, utilisez TIR SECONDAIRE pour déclencher une glissade électrique. Vous regagnez une charge de glissade toutes les deux éliminations.',
//     cName: 'VOIE RAPIDE',
//     cDesc: 'TIREZ deux lignes d\'énergie dans le sol devant vous qui parcourent une courte distance ou jusqu\'à frapper une surface. Elles s\'élèvent alors en murs d\'électricité statique qui bloquent la vision et infligent des dégâts aux ennemis qui les traversent.',
//     xName: 'ULTRA-VITESSE',
//     xDesc: 'Libérez toute la puissance et la vitesse de Neon pendant un court instant. TIREZ pour canaliser son pouvoir en un rayon électrique mortel à grande précision. Chaque élimination réinitialise sa durée.',
//     aImg: 'https://liquipedia.net/commons/images/thumb/a/a0/Neon_Fast_Lane.png/128px-Neon_Fast_Lane.png',
//     eImg: 'https://liquipedia.net/commons/images/thumb/d/df/Neon_High_Gear.png/128px-Neon_High_Gear.png',
//     cImg: 'https://liquipedia.net/commons/images/thumb/d/d8/Neon_Relay_Bolt.png/128px-Neon_Relay_Bolt.png',
//     xImg: 'https://liquipedia.net/commons/images/thumb/b/b2/Neon_Overdrive.png/128px-Neon_Overdrive.png',
//   },
//   {
//     name: 'Fade',
//     img: 'https://images.contentstack.io/v3/assets/bltb6530b271fddd0b1/blt516d37c6c84fcda0/625db737c9aa404b76ddd594/Fade_Key_Art_587x900_for_Website.png',
//     description: 'Originaire de Turquie, la chasseuse de primes Fade utilise le pouvoir des cauchemars pour s\'emparer des secrets ennemis. Elle traque ses cibles et révèle leurs plus grandes peurs pour mieux les briser dans l\'obscurité.',
//     category: 'initiateur',
//     aName: 'CAPTURE',
//     aDesc: 'Équipez-vous d\'un orbe d\'encre cauchemardesque. TIREZ pour lancer l\'orbe. Celui-ci tombe au sol après un temps défini, ce qui fait exploser l\'encre et crée une zone. Les ennemis pris dans cette zone ne peuvent pas en sortir par des moyens normaux. RÉUTILISEZ la compétence pour faire tomber le projectile plus tôt.',
//     eName: 'HANTEUR',
//     eDesc: 'Équipez-vous d\'une entité cauchemardesque. TIREZ pour lancer l\'orbe. Celui-ci tombe au sol après un temps défini, et se transforme alors en entité cauchemardesque qui révèle les ennemis dans son champ de vision. Les ennemis peuvent détruire cette entité. RÉUTILISEZ la compétence pour faire tomber le projectile plus tôt.',
//     cName: 'RÔDEUR',
//     cDesc: 'ÉQUIPEZ-vous d\'un Rôdeur. TIREZ pour envoyer le Rôdeur sur une ligne droite. Le Rôdeur se verrouille sur les ennemis ou les pistes qui entrent dans son cône de vision frontal et les suit ; s\'il atteint un ennemi, il le rend myope. MAINTENEZ la touche de TIR pour guider le Rôdeur dans la direction de votre réticule.',
//     xName: 'NUIT TOMBANTE',
//     xDesc: 'ÉQUIPEZ-vous du pouvoir de la peur. TIREZ pour envoyer une vague d\'énergie cauchemardesque qui peut traverser les murs. L\'énergie crée une piste vers l\'ennemi, en plus de l\'assourdir et de désagréger ses PV.',
//     aImg: 'https://liquipedia.net/commons/images/1/13/Fade_Seize.png',
//     eImg: 'https://liquipedia.net/commons/images/3/33/Fade_Haunt.png',
//     cImg: 'https://liquipedia.net/commons/images/d/de/Fade_Prowler.png',
//     xImg: 'https://liquipedia.net/commons/images/3/34/Fade_Nightfall.png',
//   },
//   {
//     name: 'Gekko',
//     img: 'https://images.contentstack.io/v3/assets/bltb6530b271fddd0b1/bltc942933be01094ae/6402724fe9f75310481c3851/V_AGENTS_587x900_Gekko2.png',
//     description: 'Originaire de Los Angeles, Gekko dirige une bande de créatures chaotiques, mais très soudées. Ses amis s\'occupent de disperser les ennemis, puis Gekko rassemble sa petite troupe pour recommencer.',
//     category: 'initiateur',
//     aName: 'ALTEGO',
//     aDesc: 'ÉQUIPEZ-vous d\'Altego. TIREZ pour envoyer Altego chercher des ennemis. Altego provoque une explosion désorientante en direction du premier ennemi qu\'il voit. En visant un site de pose du spike ou un spike posé, utilisez le TIR SECONDAIRE pour envoyer Altego poser ou désamorcer le spike. Pour poser le spike, Gekko doit l\'avoir dans son inventaire. Quand Altego arrive à expiration, il redevient un globule dormant. INTERAGISSEZ avec lui pour récupérer le globule et, après un court délai, pouvoir réutiliser Altego.',
//     eName: 'VERTI',
//     eDesc: 'ÉQUIPEZ-vous de Verti. TIREZ pour envoyer Verti dans les airs. Verti charge son attaque, puis tire des projectiles de plasma sur les ennemis dans son champ de vision. Les ennemis touchés par le plasma sont aveuglés. Quand Verti arrive à expiration, elle redevient un globule dormant. INTERAGISSEZ avec elle pour récupérer le globule et, après un court délai, pouvoir réutiliser Verti.',
//     cName: 'POGO',
//     cDesc: 'ÉQUIPEZ-vous de Pogo. TIREZ pour lancer Pogo comme une grenade. Utilisez le TIR SECONDAIRE pour faire un lancer par en dessous. Pogo se multiplie dans une large zone à l\'atterrissage, puis explose après un court délai.',
//     xName: 'MORDICUS',
//     xDesc: 'ÉQUIPEZ-vous de Mordicus. TIREZ pour vous lier mentalement avec Mordicus et la diriger en territoire ennemi. ACTIVEZ pour bondir vers l\'avant et exploser, ce qui retient tous les ennemis dans un petit rayon. Quand Mordicus arrive à expiration, elle redevient un globule dormant. INTERAGISSEZ avec elle pour récupérer le globule et, après un court délai, pouvoir réutiliser Mordicus. Mordicus peut être récupérée une fois.',
//     aImg: 'https://liquipedia.net/commons/images/thumb/0/0f/Gekko_Wingman.png/128px-Gekko_Wingman.png',
//     eImg: 'https://liquipedia.net/commons/images/thumb/e/ed/Gekko_Dizzy.png/128px-Gekko_Dizzy.png',
//     cImg: 'https://liquipedia.net/commons/images/thumb/c/cf/Gekko_Mosh_Pit.png/128px-Gekko_Mosh_Pit.png',
//     xImg: 'https://liquipedia.net/commons/images/thumb/3/33/Gekko_Thrash.png/128px-Gekko_Thrash.png',
//   },
// ]

const agentsToDisplay = ref(agents)

function afficher(classe) {
  agentsToDisplay.value = agents.filter(agent => agent.category === classe)
}

function showSpells(index) {
  agentCard.value[index].showModal = true
  console.log(agentCard.value[index])
}

onMounted(() => {
  console.log(agentCard.value)
})

</script>