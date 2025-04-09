


window.onload = function () {
    new Vue({
        el: "#formApp",
        data: function () {
            return {
                searchQuery: "",
                searchResults: [],
                loadingMore: false,
                allProductsLoaded: false,

                warehouses: [],
                rooms: [],
                cartoons: [],
                selectedWarehouse: "",
                selectedRoom: "",
                selectedCartoon: "",
                purchaseItems: [ ],
                subtotalAmt: 0,
                finalTotal: 0,
                dropdownVisible: false,

                other_charges_input_amount: '',
                other_charges_type: '',
                discount_on_all: '',
                discount_on_all_type: 'in_percentage',

                other_charges_amt: 0,
                discount_to_all_amt: 0,


            };
        },
        computed: {
            totalQuantity() {
                return this.purchaseItems.length > 0 
                    ? this.purchaseItems.reduce((total, item) => total + Number(item.quantity || 0), 0)
                    : 0;
            },
            subtotal() {
                return this.purchaseItems.reduce((total, item) => {
                    let price = Number(item.price || 0);
                    let quantity = Number(item.quantity || 0);
                    let discount = Number(item.discount || 0) / 100;
                    let tax = Number(item.tax || 0) / 100;

                    let totalBeforeTax = price * quantity * (1 - discount);
                    let totalWithTax = totalBeforeTax * (1 + tax);

                    return total + totalWithTax;
                }, 0);
            },
            pre_grand_total_amt() {
                return +this.subtotal + this.other_charges_amt - this.discount_to_all_amt;
            },
            grand_total_amt() {
                return Math.ceil(this.pre_grand_total_amt - this.total_round_off_amt);
            },
            // total_round_off_amt() {
            //     let num = Number(this.pre_grand_total_amt);
            //     if (isNaN(num)) return ''; // Handle invalid numbers
            //     let decimal = (num % 1).toFixed(2).substring(1); // Get only decimal part
            //     return decimal === ".00" ? "" : decimal; // Remove .00 if no decimals exist
            // }
            total_round_off_amt() {
                let num = Number(this.pre_grand_total_amt);
                if (isNaN(num)) return 0; // Return 0 instead of an empty string
                let decimal = (num % 1).toFixed(2).substring(1); // Get only decimal part
                return decimal === ".00" ? 0 : Number(decimal); // Ensure it's a number
            }
        },
        methods: {
            getData() {
                if (this.searchQuery.length > 1) {
                    this.fetchProducts();
                    this.dropdownVisible = true;
                } else {
                    this.searchResults = [];
                    this.dropdownVisible = false;
                }
            },

            calc_other_charges() {
                // console.log(this.subtotal);
                
                const subtotal = this.subtotal; 
                let percentTotal = 0;
                let fixedTotal = 0;

                var other_charges_amount = document.querySelectorAll('.other_charges_amount');
                other_charges_amount = [...other_charges_amount];
                other_charges_amount.forEach((element, index) => {
                    var type = document.querySelector('.other_charges_type' + index);
                    var value = +element.value;
                    // console.log("type.value -- ", type.value);

                    if (type.value === "percent") {
                        percentTotal += (subtotal * value) / 100;
                    } else {
                        fixedTotal += value;
                    }
                    
                });
                console.log(" percentTotal - ",  percentTotal);
                console.log(" fixedTotal - ",  fixedTotal);
                console.log(" total  - ",  percentTotal + fixedTotal);
                this.other_charges_amt = percentTotal + fixedTotal;

            },

            hideDropdown(event) {
                // Ensure the dropdown is hidden when clicking anywhere outside
                if (
                    this.$refs.searchDropdown && 
                    !this.$refs.searchDropdown.contains(event.target) && 
                    !this.$refs.searchInput.contains(event.target)
                ) {
                    this.dropdownVisible = false;
                }
            },

            toggleDropdown() {
                // Manually toggle the dropdown visibility
                this.dropdownVisible = !this.dropdownVisible;
            },

            fetchProducts() {
                this.loadingMore = true;
                axios.get(`/api/products/search?query=${this.searchQuery}`)
                    .then(response => {
                        if (response.data.length === 0) {
                            this.allProductsLoaded = true;
                        } else {
                            this.searchResults = response.data;
                            this.allProductsLoaded = true;
                        }
                    })
                    .catch(error => {
                        console.log("Error fetching products:", error);
                    })
                    .finally(() => {
                        this.loadingMore = false;
                    });
            },

            getRooms() {
                if (this.selectedWarehouse) {
                    axios.get(`/api/get-rooms/${this.selectedWarehouse}`).then((response) => {
                        this.rooms = response.data;
                        this.cartoons = [];
                    }).catch(error => {
                        console.error("Error fetching rooms:", error);
                    });
                }
            },

            getCartoons() {
                if (this.selectedRoom) {
                    axios.get(`/api/get-cartoons/${this.selectedWarehouse}/${this.selectedRoom}`).then((response) => {
                        this.cartoons = response.data;
                    }).catch(error => {
                        console.error("Error fetching cartoons:", error);
                    });
                }
            },

            addRow(product) {
                const newItem = {
                    isVisible: true,
                    id: product.id,
                    name: product.name,
                    price: product.price,
                    quantity: 1,
                    discount: 0,
                    tax: 0,
                    total: product.price * 1  // Initial total calculation
                };
                this.purchaseItems.push(newItem);
                this.searchQuery = '';  // Clear search query after adding item
                this.dropdownVisible = false;
            },

            removeRow(index) {
                this.purchaseItems.splice(index, 1);
            },

            getItemTotalPrice(item) {
                let total = item.quantity * item.price;
                if(item.discount > 0) {
                    let discountAmount = (item.discount / 100) * total;
                    total -=  discountAmount;
                }
                if(item.tax > 0) {
                    let taxAmount = (item.tax / 100) * total;                    
                    total += taxAmount;                                        
                }
                
                return total;
            }



        },
        watch: {
            searchQuery: function (newQuery) {
                if (newQuery.trim() === "") {
                    this.searchResults = [];
                    this.allProductsLoaded = false;
                    this.dropdownVisible = false;
                } else {
                    this.getData();
                }
            },

            selectedWarehouse(newWarehouse) {
                this.selectedRoom = null;
                this.getRooms();
            },
            selectedRoom(newRoom) {
                this.getCartoons();
            },

            other_charges_input_amount() {
                let other_charges = (this.other_charges_input_amount / 100) * this.subtotal;  
                console.log("other_charges - ", other_charges);
                this.other_charges_amt = other_charges;
            },

            other_charges_type() {
                let other_charges = (this.other_charges_input_amount / 100) * this.subtotal;  
                console.log("other_charges - ", other_charges);
                this.other_charges_amt = other_charges;
            },

            discount_on_all() {
                let discount_all = 0;
                this.discount_on_all = +this.discount_on_all;

                if(this.discount_on_all <= 0) {
                    this.discount_on_all = 0.0;
                    return 0.00;
                }

                if(this.discount_on_all_type == "in_percentage") {
                    discount_all = (this.discount_on_all / 100) * this.subtotal; 
                } else if(this.discount_on_all_type == "in_fixed") {
                    discount_all = +this.discount_on_all;  
                }
                
                this.discount_to_all_amt = parseFloat(discount_all);
            },

            discount_on_all_type() {
                let discount_all = 0;
                if(this.discount_on_all_type == "in_percentage") {
                    discount_all = (this.discount_on_all / 100) * this.subtotal;  
                    console.log("discount_all percent - ", discount_all);
                } else if(this.discount_on_all_type == "in_fixed") {
                    discount_all = this.discount_on_all;  
                    console.log("discount_all fixed- ", discount_all);
                }
                this.discount_to_all_amt = discount_all;
            }
           
        },

        mounted() {
            window.addEventListener("click", this.hideDropdown); // Listen for clicks on the window
        },
        beforeDestroy() {
            window.removeEventListener("click", this.hideDropdown); // Cleanup event listener
        }
    });
};






