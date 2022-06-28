<template>
    <v-container>
        <v-dialog
                v-model="showDialog"
                max-width="95%"
                persistent
        >
            <v-card color="info">
                <v-card-actions class="justify-end">
                    <v-btn
                            small
                            fab
                            @click="$emit('close')"
                            dark
                    >
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                </v-card-actions>
                <v-card-text>
                    <div class="pa-1">
                        <v-toolbar-title class="pa-1">
                            <table style="width: 100%">
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
                        <v-card class="pa-4">
                            <v-row>
                                <v-col cols="12" sm="5">
                                    <v-carousel
                                            v-if="auction.images.length != 0"
                                            hide-delimiters style="height: 100%">
                                        <v-carousel-item
                                                style="background-color: #1a202c"
                                                contain
                                                v-for="(item,i) in auction.images"
                                                :key="i"
                                                :src="require('../../../back/public/' + item.image)"
                                        ></v-carousel-item>
                                    </v-carousel>
                                    <v-carousel
                                            v-else
                                            hide-delimiters style="height: 100%">
                                        <v-carousel-item
                                                contain src="../assets/no-image-item.svg">
                                            <!--                              <v-img-->
                                            <!--                                src="../assets/no-image-item.svg"-->
                                            <!--                              ></v-img>-->
                                        </v-carousel-item>
                                    </v-carousel>
                                </v-col>
                                <v-col cols="12" sm="7">
                                    <v-row>
                                        <v-col cols="12" sm="12">
                                            <v-card-title class="text-sm-body-1">
                                                Item:
                                                <v-spacer></v-spacer>
                                                {{ auction.item.title }}
                                            </v-card-title>
                                        </v-col>
                                    </v-row>
                                    <v-col cols="12" sm="12">
                                        <hr/>
                                    </v-col>
                                    <v-row>
                                        <v-col cols="12" sm="12">
                                            <v-card-title class="text-sm-body-1">
                                                About:
                                                <v-spacer></v-spacer>
                                                <v-card-title class="text-sm-body-2" style="justify-content: end">
                                                    {{ auction.item.description }}
                                                </v-card-title>
                                            </v-card-title>
                                        </v-col>
                                    </v-row>
                                    <v-col cols="12" sm="12">
                                        <hr/>
                                    </v-col>
                                    <v-row>
                                        <v-col cols="12" sm="12">
                                            <v-card-title class="text-sm-body-1"
                                            >Warehouse:
                                                <v-spacer></v-spacer>
                                                {{ auction.item.warehouse.name }}
                                            </v-card-title>
                                        </v-col>
                                    </v-row>
                                    <v-col cols="12" sm="12">
                                        <hr/>
                                    </v-col>
                                    <v-row>
                                        <v-col cols="12" sm="12">
                                            <v-card-title class="text-sm-body-1"
                                            >Category:
                                                <v-spacer></v-spacer>
                                                {{ auction.item.category.name }}
                                            </v-card-title>
                                        </v-col>
                                    </v-row>
                                    <v-col cols="12" sm="12">
                                        <hr/>
                                    </v-col>
                                    <v-row>
                                        <v-col cols="12" sm="12">
                                            <v-card-title class="text-sm-body-1"
                                            >Condition:
                                                <v-spacer></v-spacer>
                                                {{ auction.item.condition }}
                                            </v-card-title>
                                        </v-col>
                                    </v-row>
                                </v-col>
                            </v-row>
                        </v-card>
                        <v-toolbar-title class="pa-4">
                        </v-toolbar-title>
                    </div>
                </v-card-text>
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
            itemTitle: '',
            itemDescription: '',
            itemWarehouse: '',
            itemCategory: '',
            itemSubCategory: '',
            itemCondition: '',
            images: [],
        }),

        created() {
            // console.log(this.auction)
        },
        methods: {}
    }
</script>

<style scoped>

</style>