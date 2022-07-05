<template>
    <v-container>
        <v-dialog
                v-model="showDialog"
                max-width="95%"
                persistent
        >
            <v-card color="info">
                <div class="row; pa-1" style="float: right">
                    <v-btn
                            small
                            fab
                            @click="$emit('close')"
                            dark
                    >
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                </div>

                <div class="pa-1">
                    <v-toolbar-title>
                        <table style="  width: 100%">
                            <tr>
                                <td>
                                    <hr/>
                                </td>
                                <td style="width:1px; padding: 10px; white-space: nowrap;">
                                    <h2 style="color: white">Item details</h2></td>
                                <td>
                                    <hr/>
                                </td>
                            </tr>
                        </table>

                    </v-toolbar-title>
                    <v-card>
                        <v-row class="ma-1">
                            <v-col cols="6">
                                <v-carousel
                                        :loading="dataLoading"
                                        v-if="auction.images.length != 0"
                                        v-model="test"
                                        hide-delimiters style="height: 100%">
                                    <v-carousel-item
                                            v-for="(item,i) in auction.images"
                                            :key="i"
                                    >
                                        <v-img
                                                style="background-color: #1a202c"
                                                max-height="100%"
                                                min-height="100%"
                                                contain
                                                :lazy-src="'/api/test/'+ item.image"
                                                :src="'/api/test/' + item.image"
                                        ></v-img>
                                    </v-carousel-item>
                                </v-carousel>
                                <v-carousel
                                        :loading="dataLoading"
                                        v-else
                                        hide-delimiters style="height: 100%">
                                    <v-carousel-item
                                            contain>
                                        <v-img
                                                src="../assets/no-image-item.svg"
                                        ></v-img>
                                    </v-carousel-item>
                                </v-carousel>
                            </v-col>
                            <v-col cols="6">
                                <v-row>
                                    <v-col cols="12">
                                        <v-card-title
                                                :loading="dataLoading"
                                                class="text-sm-body-1">
                                            Item:
                                            <v-spacer></v-spacer>
                                            {{ auction.item.title }}
                                        </v-card-title>
                                    </v-col>
                                </v-row>
                                <v-col cols="12">
                                    <hr/>
                                </v-col>
                                <v-row>
                                    <v-col cols="12">
                                        <v-card-title class="text-sm-body-1">
                                            About:
                                            <v-spacer></v-spacer>
                                            <v-card-title
                                                    :loading="dataLoading"
                                                    class="text-sm-body-2" style="justify-content: end">
                                                {{ auction.item.description }}
                                            </v-card-title>
                                        </v-card-title>
                                    </v-col>
                                </v-row>
                                <v-col cols="12">
                                    <hr/>
                                </v-col>
                                <v-row>
                                    <v-col cols="12">
                                        <v-card-title
                                                :loading="dataLoading"
                                                class="text-sm-body-1"
                                        >Warehouse:
                                            <v-spacer></v-spacer>
                                            {{ auction.item.warehouse.name }}
                                        </v-card-title>
                                    </v-col>
                                </v-row>
                                <v-col cols="12">
                                    <hr/>
                                </v-col>
                                <v-row>
                                    <v-col cols="12">
                                        <v-card-title
                                                :loading="dataLoading"
                                                class="text-sm-body-1"
                                        >Category:
                                            <v-spacer></v-spacer>
                                            {{ auction.item.category.name }}
                                        </v-card-title>
                                    </v-col>
                                </v-row>
                                <v-col cols="12">
                                    <hr/>
                                </v-col>
                                <v-row>
                                    <v-col cols="12">
                                        <v-card-title
                                                :loading="dataLoading"
                                                class="text-sm-body-1"
                                        >Condition:
                                            <v-spacer></v-spacer>
                                            {{ auction.item.condition }}
                                        </v-card-title>
                                    </v-col>
                                </v-row>
                            </v-col>
                        </v-row>
                    </v-card>
                </div>
            </v-card>
        </v-dialog>
    </v-container>
</template>

<script>

    export default {
        name: "ShowAdminAuctionDialog",
        props: {
            showDialog: {
                type: Boolean,
                default: false
            },
            auction: null
        },

        data: () => ({
            test: 0,
            dataLoading: false,
            itemTitle: '',
            itemDescription: '',
            itemWarehouse: '',
            itemCategory: '',
            itemSubCategory: '',
            itemCondition: '',
            images: [],
        }),

        created() {
            // this.getImgUrl()
            console.log(this.auction)
        },

        computed: {
            // // If the URL starts with ../assets/, it will be interpreted as a module request.
            // getImgUrl: function (image) {
            //     // this.dataLoading = true;
            //     // for (let i; i < this.auction.images.length; i++) {
            //     //
            //     //     // image = this.auction.images
            //     // }
            //     // if (require('../../../back/public/' + image)) {
            //     //     console.log(image)
            //     //     console.log(this.auctions.images)
            //     console.log(image)
            //     return require('../../../back/public/' + image);
            //     // }
            //     // else {
            //     //     return require('../../src/assets/images/img-carousel1.jpg')
            //     // }
            // }
        },

        methods: {
            // // If the URL starts with ../assets/, it will be interpreted as a module request.
            // getImgUrl: function (image) {
            //     // this.dataLoading = true;
            //     // for (let i; i < this.auction.images.length; i++) {
            //     //
            //     //     // image = this.auction.images
            //     // }
            //     // if (require('../../../back/public/' + image)) {
            //     //     console.log(image)
            //     //     console.log(this.auctions.images)
            //     console.log(image)
            //     return require('../../../back/public/' + image);
            //     // }
            //     // else {
            //     //     return require('../../src/assets/images/img-carousel1.jpg')
            //     // }
            // }
        }
    }
</script>

<style scoped>

</style>