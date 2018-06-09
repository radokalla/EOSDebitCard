

cleos create key
cleos create key

cleos wallet import privatekey_1 privatekey_2

cleos create account eosio dev  pubkey_1 pubkey_2

cleos set contract dev /work/EOSDebitCard/ -p dev

cleos push action dev create '["dev", "1200000000.0000 EOSD", "memo"]' -p dev