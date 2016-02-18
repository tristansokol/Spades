'use strict';

angular.module('Spades', ['LocalStorageModule', 'ngMaterial', 'google-chart']).controller('spadesCtrl', function($scope, localStorageService, $mdDialog) {
        $scope.unsupported = false;
        $scope.drawGraphsVar = 0;
        if (!localStorageService.isSupported) {
            $scope.unsupported = true;
        }
        var keys = localStorageService.keys();
        $scope.games = [];
        for (var i = 0; i < keys.length; i++) {
            console.log(keys[i])
                // console.log(localStorageService.get(keys[i]));
            $scope.games.push(JSON.parse(localStorageService.get(keys[i])));
        }

        function updateGraph(game) {
            var tmp = [['Hand', 'Players 1 & 3', 'Players 2 & 4']]
            for (var i = 0; i < game.score.player13.length; i++) {
                tmp.push([i, game.score.player13[i], game.score.player24[i]]);
            }
            $scope.graphModel = {
                dataTable: tmp,
                options:{
                	title: 'Score over Time'
                }
            }
            $scope.drawGraphsVar++;
            console.log(game);
        }

        var Game = function(id) {
            this.id = id;
            this.player1 = '';
            this.player2 = '';
            this.player3 = '';
            this.player4 = '';
            this.date = new Date();
            this.bids = {
                player1: [''],
                player2: [''],
                player3: [''],
                player4: [''],
            }
            this.takes = {
                player1: [''],
                player2: [''],
                player3: [''],
                player4: [''],
            }
            this.score = {
                player13: [0],
                player24: [0]
            }
            this.bags = {
                player13: 0,
                player24: 0
            }
            this.totalScore = {
                player13: 0,
                player24: 0
            }

            this.save = function() {
                localStorageService.set(this.id, JSON.stringify(this));
                //console.log(this);
                console.log('saving');
            }
            this.update = function() {

                this.save();
                console.log('updating');
                this.bags.player13 = 0;
                this.bags.player24 = 0;
                for (var i = 0; i < this.takes.player1.length; i++) {
                    if (!this.takes.player1[i] || !this.takes.player4[i] || !this.takes.player3[i] || !this.takes.player2[i]) {
                        break;
                    } else if (!this.bids.player1[i + 1] && !this.bids.player4[i + 1] && !this.bids.player3[i + 1] && !this.bids.player2[i + 1]) {
                        this.bids.player1[i + 1] = 0;
                    }

                    this.score.player13[i] = 0;
                    this.score.player24[i] = 0;
                    if (this.bids.player1[i] > 0 && this.bids.player3[i] > 0) {
                        if ((this.bids.player1[i] + this.bids.player3[i]) > (this.takes.player1[i] + this.takes.player3[i])) {
                            this.score.player13[i] += -10 * (this.bids.player1[i] + this.bids.player3[i]);
                        } else {
                            this.score.player13[i] += 10 * (this.bids.player1[i] + this.bids.player3[i])
                            this.bags.player13 += (this.takes.player1[i] + this.takes.player3[i]) - (this.bids.player1[i] + this.bids.player3[i]);
                        }
                    } else {
                        //either player 1 or 3 went nil or blind nil
                        if (this.bids.player1[i] < 0) {
                            //if the player went blind nil
                            if (this.takes.player1[i] > 0) {
                                this.score.player13[i] -= 200
                            } else {
                                this.score.player13[i] += 200
                            }
                        } else if (this.bids.player1[i] == 0) {
                            //if the player went blind nil
                            if (this.takes.player1[i] > 0) {
                                this.score.player13[i] -= 100
                            } else {
                                this.score.player13[i] += 100
                            }
                        } else {
                            if (this.bids.player1[i] > (this.takes.player1[i] + this.takes.player3[i])) {
                                this.score.player13[i] += -10 * (this.bids.player1[i] + this.bids.player3[i]);
                            } else {
                                this.score.player13[i] += 10 * (this.bids.player1[i] + this.bids.player3[i])
                                this.bags.player13 += (this.takes.player1[i] + this.takes.player3[i]) - (this.bids.player1[i] + this.bids.player3[i]);
                            }
                        }

                        if (this.bids.player3[i] < 0) {
                            //if the player went blind nil
                            if (this.takes.player1[i] > 0) {
                                this.score.player13[i] -= 200
                            } else {
                                this.score.player13[i] += 200
                            }
                        } else if (this.bids.player3[i] == 0) {
                            //if the player went blind nil
                            if (this.takes.player1[i] > 0) {
                                this.score.player13[i] -= 100
                            } else {
                                this.score.player13[i] += 100
                            }
                        } else {
                            if (this.bids.player3[i] > (this.takes.player1[i] + this.takes.player3[i])) {
                                this.score.player13[i] += -10 * (this.bids.player1[i] + this.bids.player3[i]);
                            } else {
                                this.score.player13[i] += 10 * (this.bids.player1[i] + this.bids.player3[i])
                                this.bags.player13 += (this.takes.player1[i] + this.takes.player3[i]) - (this.bids.player1[i] + this.bids.player3[i]);
                            }
                        }

                    }

                    //repeat for players 2 & 4
                    if (this.bids.player2[i] > 0 && this.bids.player4[i] > 0) {
                        if (this.bids.player2[i] + this.bids.player4[i] > this.takes.player2[i] + this.takes.player4[i]) {
                            this.score.player24[i] = -10 * (this.bids.player2[i] + this.bids.player4[i]);
                        } else {
                            this.score.player24[i] = 10 * (this.bids.player2[i] + this.bids.player4[i])
                            this.bags.player24 += (this.takes.player2[i] + this.takes.player4[i]) - (this.bids.player2[i] + this.bids.player4[i]);
                        }
                    } else {
                        //either player 2 or 4 went nil or blind nil
                        if (this.bids.player2[i] < 0) {
                            //if the player went blind nil
                            if (this.takes.player2[i] > 0) {
                                this.score.player24[i] -= 200
                            } else {
                                this.score.player24[i] += 200
                            }
                        } else if (this.bids.player2[i] == 0) {
                            //if the player went blind nil
                            if (this.takes.player2[i] > 0) {
                                this.score.player24[i] -= 100
                            } else {
                                this.score.player24[i] += 100
                            }
                        } else {
                            if (this.bids.player2[i] > (this.takes.player2[i] + this.takes.player4[i])) {
                                this.score.player24[i] += -10 * (this.bids.player2[i] + this.bids.player4[i]);
                            } else {
                                this.score.player24[i] += 10 * (this.bids.player2[i] + this.bids.player4[i])
                                this.bags.player24 += (this.takes.player2[i] + this.takes.player4[i]) - (this.bids.player2[i] + this.bids.player4[i]);
                            }
                        }

                        if (this.bids.player4[i] < 0) {
                            //if the player went blind nil
                            if (this.takes.player2[i] > 0) {
                                this.score.player24[i] -= 200
                            } else {
                                this.score.player24[i] += 200
                            }
                        } else if (this.bids.player4[i] == 0) {
                            //if the player went blind nil
                            if (this.takes.player2[i] > 0) {
                                this.score.player24[i] -= 100
                            } else {
                                this.score.player24[i] += 100
                            }
                        } else {
                            if (this.bids.player4[i] > (this.takes.player2[i] + this.takes.player4[i])) {
                                this.score.player24[i] += -10 * (this.bids.player2[i] + this.bids.player4[i]);
                            } else {
                                this.score.player24[i] += 10 * (this.bids.player2[i] + this.bids.player4[i])
                                this.bags.player24 += (this.takes.player2[i] + this.takes.player4[i]) - (this.bids.player2[i] + this.bids.player4[i]);
                            }
                        }

                    }


                    if (this.bags.player13 >= 10) {
                        this.bags.player13 -= 10;
                        this.score.player13[i] -= 100
                    }

                    if (this.bags.player24 >= 10) {
                        this.bags.player24 -= 10;
                        this.score.player24[i] -= 100
                    }
                    this.score.player24[i] += this.bags.player24
                    this.score.player13[i] += this.bags.player13


                }
                this.totalScore.player13 = this.score.player13.reduce(function(a, b) {
                    return a + b;
                });
                this.totalScore.player24 = this.score.player24.reduce(function(a, b) {
                    return a + b;
                })
                updateGraph(this);

            }
        }



        $scope.newGame = function() {
            $scope.currentGame = new Game(new Date().getTime());
            $scope.games.push($scope.currentGame);
            console.log('new GAme');
        }

        $scope.confirmDelete = function(id) {
            // Appending dialog to document.body to cover sidenav in docs app
            var confirm = $mdDialog.confirm()
                .title('Are you sure you want to delete this game?')
                .ariaLabel('Confirm Delete')
                // .targetEvent(ev)
                .ok('Delete')
                .cancel('Cancel');
            $mdDialog.show(confirm).then(function() {
                localStorageService.remove(id);
                for (var i = 0; i < $scope.games.length; i++) {
                    if ($scope.games[i].id == id) {
                        $scope.games.splice(i, 1);
                    }
                }
            }, function() {});
        };
        $scope.setGame = function(id) {
            //loads a game from memory, including an object merge to retain the orginal functions;
            var temp = new Game();
            console.log('loading game: ', id);
            console.log(JSON.parse(localStorageService.get(id)));

            var loadedGame = JSON.parse(localStorageService.get(id));

            for (var attrname in loadedGame) {
                temp[attrname] = loadedGame[attrname];
            }
            $scope.currentGame = temp;
            console.log($scope.currentGame);
            updateGraph($scope.currentGame);

        }





    })
    .config(function(localStorageServiceProvider) {
        localStorageServiceProvider
            .setPrefix('Spades');
    })
    .config(function($mdThemingProvider) {
        // $mdThemingProvider.theme('default')
        //   .dark();

    });
