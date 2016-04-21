# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure(2) do |config|

  config.vm.box = "ubuntu/trusty64"

  config.vm.network "forwarded_port", guest: 80, host: 8035

  config.vm.network "private_network", ip: "192.168.50.31"

  config.vm.synced_folder ".", "/vagrant"

  config.vm.provider "virtualbox" do |v|
    v.name = "finansez"
    v.memory = 1024
    v.cpus = 2
  end

  config.ssh.username = "vagrant"
  config.ssh.password = "vagrant"

  config.ssh.shell = "bash -c 'BASH_ENV=/etc/profile exec bash'"
  config.vm.provision "shell", inline: "sudo apt-get update && sudo apt-get -y install ansible python-apt"
  config.vm.provision "shell", inline: "sudo echo '[finansez]' > /etc/ansible/hosts"
  config.vm.provision "shell", inline: "sudo echo 'dev.finansez  ansible_connection=local' >> /etc/ansible/hosts"
end
