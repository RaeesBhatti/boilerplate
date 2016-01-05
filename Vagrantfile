Vagrant.configure(2) do |config|
  config.vm.box = "ubuntu/trusty64"
  config.vm.box_check_update = false
  config.vm.define "app" do |web|
    config.vm.provision "shell" do |s|
      s.path = "external/bootstrap.sh"
    end
    config.vm.network "private_network", ip: "192.168.50.2"
  end
  # From https://github.com/fgrehm/vagrant-cachier
  if Vagrant.has_plugin?("vagrant-cachier")
    config.cache.scope = :box
    config.cache.synced_folder_opts = {
      type: :nfs,
      mount_options: ['rw', 'vers=3', 'tcp', 'nolock']
    }
  end
  config.vm.provider "virtualbox" do |v|
    v.memory = 1024
    v.cpus = 2
  end
end
