<?php

// TODO Fix 81. Rd8+, illegal move

$game = <<<EOT
    1. d4 d6 2. Nf3 Nf6 3. e3 g6 4. Bd3 Bg7 5. Nbd2 O-O 6. c3 b6 7. O-O Bb7
    8. Qc2 Nbd7 9. e4 c5 10. e5 dxe5 11. dxe5 Nd5 12. c4 Nc7 13. Be4 Bxe4
    14. Nxe4 f6 15. exf6 exf6 16. Nd6 Ne8 17. Rd1 Nxd6 18. Rxd6 Qc7 19. Rd5 f5
    20. Bd2 Nf6 21. Rd3 Ne4 22. Bg5 Nxg5 23. Nxg5 Qe5 24. Rb1 Rfe8 25. Nf3 Qe2
    26. Rd2 Qe7 27. Rbd1 g5 28. Kf1 g4 29. Ng1 Bd4 30. Ne2 Qh4 31. Nxd4 Qxh2
    32. f3 cxd4 33. Qxf5 Qh1+ 34. Kf2 Qh4+ 35. Kg1 g3 36. Qg4+ Qxg4 37. fxg4 Re4
    38. Rxd4 Rxd4 39. Rxd4 Re8 40. Rd1 Re4 41. b3 Rxg4 42. Rd3 Kf7 43. Rf3+ Kg6
    44. Kf1 h5 45. Ke2 h4 46. Re3 Kf5 47. Kf3 Rf4+ 48. Ke2 Rf2+ 49. Kd3 h3
    50. gxh3 g2 51. Rg3 Rf1 52. Rxg2 Rh1 53. Rg3 Kf4 54. Re3 Rd1+ 55. Ke2 Ra1
    56. Rf3+ Kg5 57. a4 Ra2+ 58. Kd3 Ra3 59. Kc3 Ra1 60. Rg3+ Kh4 61. Rg7 Kxh3
    62. Rxa7 Rc1+ 63. Kb4 Rb1 64. Rb7 Rf1 65. Rxb6 Rf8 66. a5 Kg4 67. a6 Kf5
    68. Kb5 Ke5 69. c5 Kd5 70. b4 Rc8 71. Rd6+ Ke5 72. a7 Rg8 73. Rb6 Ra8
    74. Ka6 Kd5 75. Rb8 Rxa7+ 76. Kxa7 Kc4 77. c6 Kd5 78. c7 Kc4 79. c8=Q+ Kd3
    80. Qe8 Kd4 81. Rd8+ Kc4 82. Rb8 Kb3 83. Qc6 Ka3 84. b5 Kb3 85. b6
EOT;

include 'print-game.php';
