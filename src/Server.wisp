(ns pieshares)
(let [express (require "express")
      app (express)]
  (do
    (.use app (.bodyParser express))
    (.use app (.static express "public"))
    (.listen app 3000)))
